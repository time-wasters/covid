Krankenhausauslastung <strong>{{ Str::of($federalState['bundesland'])->title() }}</strong>: {{ round($federalState['bettenBelegtToBettenGesamtPercent']) }}%

Intensivbetten frei: {{ $federalState['intensivBettenFrei'] }}, für COVID: {{ $federalState['covidKapazitaetFrei'] }}
Intensivbetten belegt: {{ $federalState['intensivBettenBelegt'] }} von {{ $federalState['intensivBettenGesamt'] }}
... davon COVID: {{ round($federalState['faelleCovidAktuell'] / $federalState['intensivBettenBelegt'] * 100) }}%
... davon beatmet: {{ round($federalState['faelleCovidAktuellBeatmetToCovidAktuellPercent']) }}%

--
Daten abgerufen am {{ $date }}
<em>Quelle: <a href="https://www.intensivregister.de/#/aktuelle-lage/laendertabelle" title="https://www.intensivregister.de/#/aktuelle-lage/laendertabelle" target="_blank" rel="noopener noreferrer" class="text-entity-link" dir="auto">intensivregister.de</a></em>
