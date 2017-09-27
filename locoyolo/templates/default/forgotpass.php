

<div style="height:140px"></div>
<form method="post" action="">
    <table width="450" border="0" align="center" cellpadding="0" cellspacing="0" class="tableorangeborder">
        <tr>
            <td height="40" colspan="2"><p class="ArialRedBold15" style="font-weight:normal"><font class="ArialVeryDarkGreyBold20">&nbsp;&nbsp;Reset account password</font></p>
                <p class="ArialRedBold15" style="font-weight:normal">&nbsp;&nbsp;&nbsp;<span class="ArialVeryDarkGrey15">Please enter your email to receive the password reset code.</span><br />
                </p>
                <?=$error ?><?=$message ?></td>
        </tr>
        <tr>
            <td height="40" align="right" valign="middle" class="ArialOrange18"><span class="ArialVeryDarkGrey15">&nbsp;Email:</span></td>
            <td class="ArialOrange18">
                &nbsp;<input name="email" type="text" class="textboxbottomborder" id="email" size="40" /></td>
        </tr>
        <tr>
            <td height="40" colspan="2" align="center" class="ArialOrange18"><table align="center">
                    <tr>
                        <td height="29"><input class="standardbutton" style="cursor:pointer" type="submit" id="submit" name="submit" value="Submit"></td>
                    </tr>
                </table>
                <div style="height:10px"></div>
            </td>
        </tr>
    </table>
</form>


