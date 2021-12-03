@foreach ($counties as $county)
<ins>{{ $county['GEN'] }} ({{ $county['BEZ'] }})</ins>
Fälle (gesamt):            <em>{{ $county['cases'] }}</em> [+X]
7Tage/100K EW:         <strong>{{ $county['cases7_per_100k_txt'] }}</strong> [-X]
Fälle letzte 7 Tage:     <strong>{{ $county['cases7_lk'] }}</strong> [-X]
Todesfälle (gesamt):  <em>{{ $county['deaths'] }}</em> [+X]

@endforeach
{{ $date }}
<i>Quelle:</i> <a href="https://experience.arcgis.com/experience/478220a4c454480e823b17327b2bf1d4" title="https://experience.arcgis.com/experience/478220a4c454480e823b17327b2bf1d4" target="_blank" rel="noopener noreferrer" class="text-entity-link" dir="auto">RKI</a>
