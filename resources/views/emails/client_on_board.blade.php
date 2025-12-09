<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge" />
        <title>{{ $data['subject'] }}</title>

        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet" />
    </head>

    <body
        style=" margin: 0; font-family: 'Poppins', sans-serif; background: #ffffff; font-size: 14px; ">
        <div
            style=" max-width: 680px; margin: 0 auto; padding: 45px 30px 60px; font-size: 14px; color: #000; ">
            <header>
                <table style="width: 100%;">
                    <tbody>
                        <tr style="height: 0;">
                            <td>
                                <img alt="Devotion Group" src="{{url('public/img/devotion-group.jpg')}}" height="30px" />
                            </td>
                            <td style="text-align: center;">
                                <span style="font-size: 16px; line-height: 30px;">{{date( 'd M, y h:i' )}}</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </header>

            <main>
                <div style=" margin: 0; margin-top: 50px; padding: 50px; background: #ab8134; border-radius: 30px; text-align: center; color: #fff; ">
                    <div style="width: 100%; max-width: 489px; margin: 0 auto;">
                        <h1 style=" margin: 0; font-size: 28px; font-weight: 500; color: #fff; ">Welcome, {{$data['name']}} </h1>
                        <p style=" margin: 0; margin-top: 17px; font-weight: 500; letter-spacing: 0.56px; ">
                            Thank you for joining our platform. We're excited to have you onboard! If you have any questions or need assistance, feel free to reach out to us.
                        </p>
                    </div>
                </div>
            </main>

            <footer
                style=" width: 100%; max-width: 490px; margin: 20px auto 0; text-align: center; border-top: 1px solid #e6ebf1; ">
                <p
                    style=" margin: 0; margin-top: 40px; font-size: 16px; font-weight: 600; color: #434343; ">
                    Devotion Group
                </p>
                <p style="margin: 0; margin-top: 8px; color: #434343;">
                    2801, 28th floor, Zone A, Aspect Tower, Bay Avenue Business Bay, Dubai - UAE
                </p>

                <p style="margin: 0; margin-top: 16px; color: #434343;">
                    Copyright © {{date('Y')}} Company. All rights reserved.
                </p>
            </footer>
        </div>
    </body>

</html>