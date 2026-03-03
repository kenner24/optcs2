<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" href="../../favicon.ico">
        <title>Email Template</title>
        <style>
            *
            {
                margin: 0 auto !important;
                padding: 0 !important;
            }
            
            img
            {
                border: 0 !important;
                line-height: 0;
            }
            
            body
            {
                margin: 0 auto !important;
            }
            
            a
            {
                text-decoration: none !important;
            }
            
        </style>
    </head>
    <body>
        <center>
            <div style="width: 600px; margin: 0 auto; display:block; background-color: #fff;">
                <center>
                    <table width="600" style="font-family: Arial !important; background-color: #fff;" cellspacing="0" cellpadding="0" border="0" align="center">
                        <tr>
                            <td>
                                <center><img src="{{URL::asset('image/email/logo.png')}}" alt="img" style="display:block; max-width: 100%" /></center>
                            </td>
                        </tr>
                        <tr style="display: flex;">
                            <td><img src="{{URL::asset('image/email/spacer.png')}}" style="display:block;"></td>
                            <td style="background-image: url({{URL::asset('image/email/bg-img.jpg')}}); background-size: cover;background-repeat: no-repeat; width: 100%;">
                                <table width="100%">
                                    <tr style="display: flex;">
                                        <td><img src="{{URL::asset('image/email/spacer.png')}}" style="display:block;"></td>
                                        <td style="width: 100%">
                                            <table width="100%">
                                                <tr>
                                                    <td>
                                                        <center><img src="{{URL::asset('image/email/verticalspacer.png')}}" style="display:block;" /></center>
                                                    </td>
                                                </tr>
                                                <tr style="background-color: #000000cc;">
                                                    <td>
                                            <table width="100%">
                                                <tr>
                                                    <td><img src="{{URL::asset('image/email/spacer.png')}}" style="display:block;"></td>
                                                    <td style="width: 100%">
                                                        <table width="100%">
                                                            <tr>
                                                                <td>
                                                                    <center><img src="{{URL::asset('image/email/verticalspacer.png')}}" style="display:block;" /></center>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="color: #fff;font-size: 26px;
                                                                  font-weight: 600;"><center>Saas || Email Verification<center></td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <center><img src="{{URL::asset('image/email/verticalspacer.png')}}" style="display:block;" /></center>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                    <td><img src="{{URL::asset('image/email/spacer.png')}}" style="display:block;"></td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <center><img src="{{URL::asset('image/email/verticalspacer.png')}}" style="display:block;" /></center>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td><img src="{{URL::asset('image/email/spacer.png')}}" style="display:block;"></td>
                                    </tr>
                                </table>
                            </td>
                            <td><img src="{{URL::asset('image/email/spacer.png')}}" style="display:block;"></td>
                        </tr>

                        <tr style="display: flex;">
                            <td><img src="{{URL::asset('image/email/spacer.png')}}" style="display:block;"></td>
                            <td style="width: 100%">
                                <table style="width: 100%;">
                                    <tr>
                                        <td>
                                            <center><img src="{{URL::asset('image/email/verticalspacer.png')}}" style="display:block;" /></center>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <center><img src="{{URL::asset('image/email/verticalspacer.png')}}" style="display:block;" /></center>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 16px; color: #000; font-weight: 600;">
                                            Dear {{ @$name }},
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 14px; color: #000; font-weight: 400; line-height: 24px;">
                                            Please click the following button to verify.
                                        </td>
                                    </tr>
                                    <tr>
                                      <td style="padding:15px 10px; font-size:16px; color:#4d5763;">
                                         {!!$link!!}
                                      </td>

                                   </tr>
                                    <tr>
                                        <td>
                                            <center><img src="{{URL::asset('image/email/verticalspacer.png')}}" style="display:block;" /></center>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 16px; color: #000; font-weight: 600; line-height: 24px;">
                                            <p>Regards<p>
                                            <p>Saas Team<p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td><img src="{{URL::asset('image/email/spacer.png')}}" style="display:block;"></td>
                        </tr>

                        <tr>
                            <td>
                                <center><img src="{{URL::asset('image/email/verticalspacer.png')}}" style="display:block;" /></center>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <center><img src="{{URL::asset('image/email/verticalspacer.png')}}" style="display:block;" /></center>
                            </td>
                        </tr>

                        <tr style="display: flex; border-top: 1px solid #ccc;">
                            <td><img src="{{URL::asset('image/email/spacer.png')}}" style="display:block;"></td>
                            <td style="width: 100%">
                                <table style="width: 100%;">
                                    <tr height="20"></tr>
                                    <tr>
                                        <td>
                                            <!-- <center>
                                               <ul>
                                                    <li style="display: inline-block;"><a href="https://www.facebook.com/LOGYTalks/"><img style="width: 20px;" src="{{URL::asset('image/email/icon1.png')}}" alt=""></a></li>
                                                    <li style="display: inline-block;"><a href="https://twitter.com/LogyTalks"><img style="width: 20px;" src="{{URL::asset('image/email/icon2.png')}}" alt=""></a></li>
                                                    <li style="display: inline-block;"><a href="https://www.linkedin.com/company/logytalks/"><img style="width: 20px;" src="{{URL::asset('image/email/icon3.png')}}" alt=""></a></li>
                                                    <li style="display: inline-block;"><a href="https://www.instagram.com/logytalks/"><img style="width: 20px;" src="{{URL::asset('image/email/icon4.png')}}" alt=""></a></li>
                                                    <li style="display: inline-block;"><a href="https://in.pinterest.com/logytalksinc/"><img style="width: 20px;" src="{{URL::asset('image/email/icon5.png')}}" alt=""></a></li>
                                                </ul>
                                            </center> -->
                                        </td>
                                    </tr>
                                    <tr height="5"></tr>
                                    <tr>
                                        <td style="font-size: 14px; color: #cccdcd;">
                                            <center>© Saas {{ @$year }}. All rights Reserved.
</center>
                                        </td>
                                    </tr>
                                    <tr height="20"></tr>
                                </table>
                            </td>
                            <td><img src="{{URL::asset('image/email/spacer.png')}}" style="display:block;"></td>
                        </tr>
                        
                    </table>
                </center>
            </div>
        </center>
    </body>
</html>



