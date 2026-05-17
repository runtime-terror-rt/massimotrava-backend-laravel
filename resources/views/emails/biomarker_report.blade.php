<!DOCTYPE html>
<html>
<body>
    <h2>Hello, {{ $user->name }}</h2>
    <p>Your biomarker medical report for the invoice <strong>{{ $reports->first()->inv_code }}</strong> has been generated successfully.</p>
    <p>Please find the attached PDF file for your detailed report.</p>
    <br>
    <p>Best Regards,<br>Massimotrava Team</p>
</body>
</html>