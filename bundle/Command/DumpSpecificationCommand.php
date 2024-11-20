<?php

declare(strict_types=1);

namespace Netgen\Bundle\OpenApiIbexaBundle\Command;

use Netgen\OpenApiIbexa\OpenApi\OpenApiFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

use function json_encode;

use const JSON_PRETTY_PRINT;
use const JSON_THROW_ON_ERROR;
use const JSON_UNESCAPED_SLASHES;

final class DumpSpecificationCommand extends Command
{
    private SymfonyStyle $io;

    public function __construct(
        private OpenApiFactory $openApiFactory,
        private NormalizerInterface $normalizer,
    ) {
        parent::__construct();
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->io = new SymfonyStyle($input, $output);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $data = $this->normalizer->normalize(
            $this->openApiFactory->buildModel(),
            'json',
            [AbstractObjectNormalizer::SKIP_NULL_VALUES => true],
        );

        $this->io->write(json_encode($data, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES));

        return Command::SUCCESS;
    }
}
