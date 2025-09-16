<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Database Connection</title>
</head>
<body>
    <div>
        @if(DB::connection()->getPdo())
            <p>Success! The database name is: {{ DB::connection()->getDatabaseName() }}</p>
        @else
            <p>Failed to connect to the database.</p>
        @endif
    </div>
</body>
</html>
