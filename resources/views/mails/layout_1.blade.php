<table class="body-wrap" width="600" style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; width: 600px; background-color: transparent; margin: 0;">
    <tr style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
        <td class="container" width="600" style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; display: block !important; max-width: 600px !important; clear: both !important;" valign="top">
            <div class="content" style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; max-width: 600px; display: block; margin: 0 auto; padding: 20px;">
                <table class="main" width="100%" cellpadding="0" cellspacing="0" itemprop="action" itemscope itemtype="http://schema.org/ConfirmAction" style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; border-radius: 3px; margin: 0; border: none;">
                    <tr style="font-family: 'Roboto', sans-serif; font-size: 14px; margin: 0;">
                        <td class="content-wrap" style="font-family: 'Roboto', sans-serif; box-sizing: border-box; color: #495057; font-size: 14px; vertical-align: top; margin: 0;box-shadow: 0 3px 15px rgba(30,32,37,.06); ;border-radius: 7px; background-color: #fff;overflow: hidden;" valign="top">
                            <meta itemprop="name" content="Confirm Email" style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;" />
                            <div style="padding: 20px;box-sizing: border-box; text-align: center; background: #000000;">
                                <img src="{{ businessAssetPath('/common/images/logo-white.png') }}" alt="" height="25">
                            </div>

                            @yield('content')

                            <div style="padding: 20px;box-sizing: border-box; text-align: center; background-color: #000000;">
                                <h6 style="font-family: 'Roboto', sans-serif;margin: 0; font-size: 15px;color: #fff;"><a href="mailto:contact@sevn.lk" style="color: #fff; text-decoration: none;">contact@sevn.lk</a></h6>
                            </div>
                            <div style="padding: 20px;box-sizing: border-box; text-align: center;">
                                <p style="font-family: 'Roboto', sans-serif;margin-bottom: 0px;font-weight: 500;color: #98a6ad;">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam</p>
                            </div>
                        </td>
                    </tr>
                </table>
                <div style="text-align: center; margin: 28px auto 0px auto;">
                    <p style="font-family: 'Roboto', sans-serif; font-size: 14px;color: #98a6ad; margin: 0px 0px 5px 0px;">{{ date('Y', time()) }} admin @ sevn.lk. All rights reserved.</p>
                </div>
            </div>
        </td>
    </tr>
</table>
