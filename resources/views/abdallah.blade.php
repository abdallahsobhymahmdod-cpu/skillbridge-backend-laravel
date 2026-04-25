<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>I'M</title>
</head>
<body>
    @if ($grads > 70)
    <div style="background-color: hotpink; color: wheat">
        <h1>grads greater than 70</h1>
    </div>
    @elseif ($grads < 70)
    <div style="background-color: green; color: wheat">
        <h1>grads less than 70</h1>
    </div>
    @else
    <div style="background-color: blue; color: wheat">
        <h1>grads equal to 70</h1>
    </div>
    @endif
    <h1>Abdallah , {{ $Last_name }}</h1>
    <p>{{ $details }}</p>

    @for ($i = 0; $i<=10;$i++)
    <h2>the value of i is: {{ $i }}</h2>
    
    @endfor

    <div style="padding: 20%; display: flex; justify-content: space-between; size: 50%; background-color: black; color: white; font-size: large;">
    @foreach ($student as $s)
    <h3>{{ $s  }}</h3>
    @endforeach
    </div>
</body>
</html>