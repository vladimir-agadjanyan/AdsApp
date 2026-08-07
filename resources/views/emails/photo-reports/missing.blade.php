<h2>Отсутствует фотоотчет</h2>

<p>
    По рекламному объекту
    <strong>{{ $advertisingObject->name }}</strong>
    не был загружен фотоотчет за
    <strong>{{ today()->format('d.m.Y') }}</strong>.
</p>

<p>
    Договор:
    <strong>№{{ $advertisingObject->contract->number }}</strong>
</p>

<p>
    Тип рекламы:
    <strong>{{ $advertisingObject->advertisingType->name }}</strong>
</p>

<p>
    Адрес:
    <strong>{{ $advertisingObject->address }}</strong>
</p>

<hr>

<p>
    Просим загрузить фотоотчет по данному рекламному объекту.
</p>

<p>
    С уважением,<br>
    AdsApp
</p>