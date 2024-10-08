    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Job Notification Email</title>
    </head>

    <body>
        <h1>Hello {{ $mailData['employer']->name }}</h1>
        <p>JOb Title: {{ $mailData['job']->title }}</p>

        <p>Employe Details</p>
        <p>Name: {{ $mailData['user']->name }}</p>
        <p>email: {{ $mailData['user']->email }}</p>
        <p>mobile: {{ $mailData['user']->mobile }}</p>
    </body>

    </html>
