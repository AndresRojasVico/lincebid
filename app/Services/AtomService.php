<?php

namespace App\Services;

use App\Models\Proyecto;
use Illuminate\Support\Facades\Storage;
use SimpleXMLElement;
use DateTime;

class AtomService
{
    // Extraemos los códigos a una constante para limpiar el código
    const CPV_FILTERS = [30000000, 30100000, 30120000, 30121100, 30123000, 30123100, 30140000, 30141000, 30141100, 30141200, 30141300, 30150000, 30151000, 30170000, 30172000, 30190000, 30191000, 30191400, 30192000, 30192100, 30192200, 30192300, 30192400, 30192500, 30192600, 30192700, 30192800, 30192900, 30193000, 30193100, 30193200, 30197000, 30197100, 30197200, 30197300, 30197400, 30197500, 30197600, 30199000, 30199100, 30199200, 30199300, 30199400, 30199500, 30199600, 30199700, 30200000, 30210000, 30211000, 30211100, 30211200, 30211300, 30211400, 30211500, 30212000, 30212100, 30213000, 30213100, 30213200, 30213300, 30213400, 30213500, 30214000, 30215000, 30215100, 30216000, 30216100, 30230000, 30231000, 30231100, 30231200, 30231300, 30232000, 30232100, 30233000, 30233100, 30233300, 30234000, 30234100, 30234200, 30234300, 30234400, 30234500, 30234600, 30234700, 30236000, 30236100, 30236200, 30237000, 30237100, 30237200, 30237300, 30237400, 32000000, 32200000, 32250000, 32251000, 32251100, 32252000, 32252100, 32300000, 32320000, 32340000, 32341000, 32342000, 32342100, 32342200, 32342300, 32400000, 32410000, 32420000, 32421000, 32422000, 32423000, 32424000, 48000000, 51000000, 51600000, 51610000, 51611000, 51611100, 51612000, 72000000, 72100000, 72200000, 72400000, 72500000, 72600000, 72700000, 72900000];

    public function processXmlContent(string $xmlContent): array
    {
        $xml = new SimpleXMLElement($xmlContent);
        $processedData = [];

        foreach ($xml->entry as $entry) {
            $namespaces = $entry->getNamespaces(true);

            // 1. Extraer códigos CPV primero para filtrar rápido
            $currentCodes = $this->extractCodes($entry, $namespaces);

            if (!$this->hasMatchingCode($currentCodes)) {
                continue;
            }

            // 2. Parsear el resto de datos (Mapeo)
            $processedData[] = $this->mapEntryToData($entry, $namespaces);
        }

        return $processedData;
    }

    public function syncProyectos(array $data): void
    {
        foreach ($data as $item) {
            Proyecto::updateOrCreate(
                ['expediente' => $item['expediente']], // Único
                $item // Datos a actualizar o crear
            );
        }
    }

    private function mapEntryToData($entry, $namespaces): array
    {
        // Registro de namespaces para XPath
        foreach ($namespaces as $prefix => $uri) {
            $entry->registerXPathNamespace($prefix ?: 'ns', $uri);
        }

        return [
            'expediente' => (string) $entry->children($namespaces['cac-place-ext'])->ContractFolderStatus->children($namespaces['cbc'])->ContractFolderID,
            'link' => (string) $entry->link['href'],
            'summary' => (string) $entry->summary,
            'fecha_actualizacion' => (new DateTime($entry->updated))->format('Y-m-d H:i:s'),
            'estado' => $this->parseEstado($entry, $namespaces),
            'importe' => (string) $entry->children($namespaces['cac-place-ext'])->ContractFolderStatus->children($namespaces['cac'])->ProcurementProject->children($namespaces['cac'])->BudgetAmount->children($namespaces['cbc'])->TaxExclusiveAmount,
            'objeto_contrato' => (string) $entry->children($namespaces['cac-place-ext'])->ContractFolderStatus->children($namespaces['cac'])->ProcurementProject->children($namespaces['cbc'])->Name,
            // ... resto de campos
        ];
    }

    private function parseEstado($entry, $namespaces): string
    {
        $code = (string) $entry->children($namespaces['cac-place-ext'])->ContractFolderStatus->children($namespaces['cbc-place-ext'])->ContractFolderStatusCode;
        $map = [
            'PRE' => 'ANUNCIO PREVIO',
            'PUB' => 'EN PLAZO',
            'EV' => 'PENDIENTE',
            'ADJ' => 'ADJUDICADA',
            'RES' => 'RESUELTA',
            'ANU' => 'ANULADA',
            'ARC' => 'ARCHIVADA'
        ];
        return $map[$code] ?? 'DESCONOCIDO';
    }

    private function extractCodes($entry, $namespaces): array
    {
        $codes = [];
        $co = $entry->children($namespaces['cac-place-ext'])->ContractFolderStatus->children($namespaces['cac'])->ProcurementProject->children($namespaces['cac'])->RequiredCommodityClassification;
        foreach ($co as $code) {
            $codes[] = (int) $code->children($namespaces['cbc'])->ItemClassificationCode;
        }
        return $codes;
    }

    private function hasMatchingCode(array $codes): bool
    {
        return !empty(array_intersect($codes, self::CPV_FILTERS));
    }
}
