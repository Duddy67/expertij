<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Subscription Invoice</title>
</head>

<body>

<table width="100%" border="0" cellpadding="5" cellspacing="5">
  <tbody>
    <tr>
      <th scope="col">
        <table style="text-align: left" width="100%" border="0" cellpadding="0" cellspacing="0">
          <tbody>
            <tr>
              <td style="width:40%; text-align: left"><img height="120px" width="195px" src="{{ public_path('images').'/expertij/pdf/logo-expertij-red.png' }}"></td>
                <td style="width:60%">
                  <h3>Des traducteurs-interprètes <br />au service de la justice et des justiciables</h3>
                  <p style="font-weight: 300">Association loi 1901 <br />N° d’organisme formateur : 11 75 54854 75</p>
                </td>
            </tr>
          </tbody>
        </table>
      </th>
    </tr>
    <tr>
      <td>
        <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tbody>
            <tr>
              <td style="width:100%">
                <p><strong>{{ $civility }} {{ $first_name }} {{ $last_name }}<br/>{{ $street }}<br/>{{ $postcode }} {{ $city }}</strong></p>
                <p>Membre no: <strong>{{ $member_number }}</strong></p>
                <p>Objet: Reçu de cotisation</p> 
              </td>
            </tr>
          </tbody>
        </table>
      </td>
    </tr>
    <tr>
      <td>
        <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tbody>
            <tr>
              <td style="width:100%">
                <p>Cher membre,</p>
                <p>Nous avons bien reçu le règlement de votre cotisation annuelle <strong>{{ $subscription_year }}</strong> et nous vous en remercions.<br/>
        Pour les besoins de votre comptabilité, nous attestons par la présente que vous avez dûment acquitté auprès de notre association un montant de <strong>{{ $subscription_fee }}</strong> euros.</p>
                <p> Nous vous rappelons que la cotisation n’est pas soumise à la TVA et qu’elle ne donne pas lieu à la délivrance d’une facture. Elle n’ouvre pas droit au bénéfice des dispositions des articles 200, 238 bis et 885-0 V bis A du code général des impôts.</p>
                          <br />
                <p>Nous vous prions d’agréer, cher membre, nos sincères salutations.</p>
                          <br />
                <p>Pour l’association<br/>
        Le {{ $current_date }} <br/>
        La trésorière
                </p>
                <br/><br/><br/><br/><br/>
              </td>
            </tr>
          </tbody>
        </table>
            </td>
            </tr>
            <tr>
              <td>
                <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tbody>
    <tr>
      <td style="width:33%; text-align:center;">
		  <img height="40px" width="100px" src="{{ public_path('images').'/expertij/pdf/logo-expertij-red.png' }}">
		</td>
      <td style="width:33%; text-align:center;">
		  <img src="{{ public_path('images').'/expertij/pdf/qualiopi.png' }}">
		</td>
		<td style="width:33%; text-align:center;">
		  <img src="{{ public_path('images').'/expertij/pdf/eulita.png' }}">
		</td>
          </tr>
        </tbody>
      </table>
      </td>
      </tr>
      <tr>
      <td>
        <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tbody>
            <tr>
              <td style="text-align:center;">
                <br /><br />
                <p style="text-align: center;">Siège social : c/o. CERTEX-CALITEX - 31 rue du Rocher – 75008 Paris <br /> contact@expertij.fr - www.expertij.fr</p>
              </td>
            </tr>
        </tbody>
      </table>
    </td>
  </tr>
  </tbody>
</table>
</body>

</html>
