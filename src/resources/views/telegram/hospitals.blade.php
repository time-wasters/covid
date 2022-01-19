Krankenhausauslastung {{ Str::of($federalState['bundesland'])->title() }}: <ins><strong>{{ round($federalState['bettenBelegtToBettenGesamtPercent']) }}%</strong></ins>

Intensivbetten frei: {{ $federalState['intensivBettenFrei'] }}, für COVID: {{ $federalState['covidKapazitaetFrei'] }}
Intensivbetten belegt: {{ $federalState['intensivBettenBelegt'] }} von {{ $federalState['intensivBettenGesamt'] }}
... davon COVID: {{ $federalState['faelleCovidAktuell'] }} (<strong>≈{{ round($federalState['faelleCovidAktuell'] / $federalState['intensivBettenBelegt'] * 100) }}%</strong>)
... davon beatmet: {{ $federalState['faelleCovidAktuellBeatmet'] }} (≈{{ round($federalState['faelleCovidAktuellBeatmetToCovidAktuellPercent']) }}%)

--
Daten abgerufen am {{ $date }}
<em>Quelle: <a href="https://www.intensivregister.de/#/aktuelle-lage/laendertabelle" title="https://www.intensivregister.de/#/aktuelle-lage/laendertabelle" target="_blank" rel="noopener noreferrer" class="text-entity-link" dir="auto">intensivregister.de</a></em>
