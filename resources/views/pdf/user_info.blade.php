<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foydalanuvchi Ma'lumotlari</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>Foydalanuvchi Ma'lumotlari</h1>

    <table>
        <tr>
            <th>Ma'lumot</th>
            <th>Qiymat</th>
        </tr>
        <tr>
            <td>Ism</td>
            <td>{{ $data['Name'] }}</td>
        </tr>
        <tr>
            <td>Email</td>
            <td>{{ $data['Email'] }}</td>
        </tr>
        <tr>
            <td>Registered</td>
            <td>{{ $data['Registered'] }}</td>
        </tr>
    </table>
</body>
</html>
