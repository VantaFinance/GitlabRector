<?php
/**
 * Gitlab rector
 *
 * @author Vlad Shashkov <v.shashkov@pos-credit.ru>
 * @copyright Copyright (c) 2023, The Vanta
 */

declare(strict_types=1);

namespace Vanta\Integration\Rector;

use Nette\Utils\Strings;
use Rector\ChangesReporting\Annotation\RectorsChangelogResolver;
use Rector\ChangesReporting\Contract\Output\OutputFormatterInterface as OutputFormatter;
use Rector\Core\ValueObject\Configuration;
use Rector\Core\ValueObject\ProcessResult;
use Rector\Core\ValueObject\Reporting\FileDiff;
use Vanta\Integration\Rector\CodeQuality\Line;
use Vanta\Integration\Rector\CodeQuality\Location;
use Vanta\Integration\Rector\CodeQuality\Report;
use Vanta\Integration\Rector\CodeQuality\Severity;
use Violet\StreamingJsonEncoder\BufferJsonEncoder;

final readonly class GitlabOutputFormatter implements OutputFormatter
{
    /**
     * @var non-empty-string
     */
    public const NAME = 'gitlab';

    public function __construct(
        private RectorsChangelogResolver $rectorsChangelogResolver,
        private Severity $errorsSeverity = Severity::CRITICAL,
        private Severity $diffSeverity = Severity::BLOCKER,
    ) {
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function report(ProcessResult $processResult, Configuration $configuration): void
    {
        $bufferJsonEncoder = new BufferJsonEncoder(function () use ($processResult) {
            foreach ($processResult->getSystemErrors() as $systemError) {
                $path      = $systemError->getRelativeFilePath();
                $beginLine = $systemError->getLine();
                /** @var non-empty-string $message */
                $message = $systemError->getMessage();

                yield new Report(
                    $message,
                    'rectorError',
                    $this->errorsSeverity,
                    $path !== null && $beginLine !== null ? new Location($path, new Line($beginLine)) : null
                );
            }

            foreach ($processResult->getFileDiffs() as $fileDiff) {
                $logs = $this->createRectorChangelogLines($fileDiff);

                foreach ($fileDiff->getRectorChanges() as $change) {
                    $rule = $logs[$change->getRectorClass()];
                    $line = $change->jsonSerialize()['line'];


                    yield new Report(
                        $rule,
                        $rule,
                        $this->diffSeverity,
                        new Location($fileDiff->getRelativeFilePath(), new Line($line)),
                        $fileDiff->getDiff()
                    );
                }
            }
        });

        foreach ($bufferJsonEncoder as $error) {
            echo $error;
        }
    }



    /**
     * @return array<class-string, non-empty-string>
     */
    private function createRectorChangelogLines(FileDiff $fileDiff): array
    {
        $rectorsChangelogs      = $this->rectorsChangelogResolver->resolveIncludingMissing($fileDiff->getRectorClasses());
        $rectorsChangelogsLines = [];

        foreach ($rectorsChangelogs as $rectorClass => $changelog) {
            $rectorShortClass = (string) Strings::after($rectorClass, '\\', -1);
            /** @var non-empty-string $line */
            $line                                 = $changelog === null ? $rectorShortClass : $rectorShortClass . ' (' . $changelog . ')';
            $rectorsChangelogsLines[$rectorClass] = $line;
        }

        return $rectorsChangelogsLines;
    }
}
