<!DOCTYPE html>
<html>
<head>
    <title>API Response</title>
</head>
<body>
    <h1>API Response Data</h1>

    @if(isset($data))
        @foreach($data as $key => $value)
            <h2>{{ ucfirst($key) }}</h2>
            @if(is_array($value))
                <ul>
                    @foreach($value as $subKey => $subValue)
                        <li><strong>{{ ucfirst($subKey) }}:</strong>
                            @if(is_array($subValue))
                                <ul>
                                    @foreach($subValue as $itemKey => $itemValue)
                                        <li><strong>{{ ucfirst($itemKey) }}:</strong> {{ $itemValue }}</li>
                                    @endforeach
                                </ul>
                            @else
                                {{ $subValue }}
                            @endif
                        </li>
                    @endforeach
                </ul>
            @else
                <p>{{ $value }}</p>
            @endif
        @endforeach
    @else
        <p>No data received.</p>
    @endif
</body>
</html>
