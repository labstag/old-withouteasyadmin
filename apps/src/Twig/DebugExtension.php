<?php

namespace Labstag\Twig;

use Labstag\Lib\ExtensionLib;
use Twig\TwigFilter;
use Twig\TwigFunction;

class DebugExtension extends ExtensionLib
{

    protected array $templates = [];

    public function debugBegin(array $data): string
    {
        if (0 == (is_countable($data) ? count($data) : 0)) {
            return '';
        }

        return $this->beginDebug($data);
    }

    public function debugBeginForm(mixed $class): string
    {
        $data = $this->getformClassData($class);
        if (0 == (is_countable($data) ? count($data) : 0)) {
            return '';
        }

        return $this->beginDebug($data);
    }

    public function debugBeginPrototype(array $blockPrefixes): string
    {
        $data = $this->formPrototypeData($blockPrefixes);
        if (0 == (is_countable($data) ? count($data) : 0)) {
            return '';
        }

        return $this->beginDebug($data);
    }

    public function debugEnd(array $data): string
    {
        if (0 == (is_countable($data) ? count($data) : 0)) {
            return '';
        }

        return $this->endDebug($data);
    }

    public function debugEndForm(mixed $class): string
    {
        $data = $this->getformClassData($class);
        if (0 == (is_countable($data) ? count($data) : 0)) {
            return '';
        }

        return $this->endDebug($data);
    }

    public function debugEndPrototype(array $blockPrefixes): string
    {
        $data = $this->formPrototypeData($blockPrefixes);
        if (0 == (is_countable($data) ? count($data) : 0)) {
            return '';
        }

        return $this->endDebug($data);
    }

    /**
     * @return TwigFilter[]
     */
    public function getFilters(): array
    {
        $dataFilters = $this->getFiltersFunctions();
        $filters     = [];
        foreach ($dataFilters as $key => $function) {
            /** @var callable $callable */
            $callable = [
                $this,
                $function,
            ];
            $filters[] = new TwigFilter($key, $callable, ['is_safe' => ['all']]);
        }

        return $filters;
    }

    public function getFiltersFunctions(): array
    {
        return [
            'debug_begin_prototype' => 'debugBeginPrototype',
            'debug_end_prototype'   => 'debugEndPrototype',
            'debug_begin_form'      => 'debugBeginForm',
            'debug_end_form'        => 'debugEndForm',
            'debug_begin'           => 'debugBegin',
            'debug_end'             => 'debugEnd',
        ];
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        $dataFunctions = $this->getFiltersFunctions();
        $functions     = [];
        foreach ($dataFunctions as $key => $function) {
            /** @var callable $callable */
            $callable = [
                $this,
                $function,
            ];
            $functions[] = new TwigFunction($key, $callable, ['is_safe' => ['all']]);
        }

        return $functions;
    }

    private function beginDebug(array $data): string
    {
        $html = "<!--\nTHEME DEBUG\n";
        $html .= "THEME HOOK : '".$data['hook']."'\n";
        if (0 != (is_countable($data['files']) ? count($data['files']) : 0)) {
            $html .= "FILE NAME SUGGESTIONS: \n";
            foreach ($data['files'] as $file) {
                $checked = ($data['view'] == $file) ? 'X' : '*';
                $html .= ' '.$checked.' '.$file."\n";
            }
        }

        return $html.("BEGIN OUTPUT from '".$data['view']."' -->\n");
    }

    private function endDebug(array $data): string
    {
        return "\n<!-- END OUTPUT from '".$data['view']."' -->\n";
    }
}
