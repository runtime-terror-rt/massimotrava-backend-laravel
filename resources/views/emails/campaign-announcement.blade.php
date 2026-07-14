<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $campaign->title }}</title>
<!--[if mso]>
<noscript>
  <xml>
    <o:OfficeDocumentSettings>
      <o:PixelsPerInch>96</o:PixelsPerInch>
    </o:OfficeDocumentSettings>
  </xml>
</noscript>
<![endif]-->
</head>
<body style="margin:0; padding:0; background-color:#05070b; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%;">

  <!-- Preheader (hidden preview text in inbox) -->
  <div style="display:none; max-height:0; overflow:hidden; opacity:0;">
    {{ Str::limit(strip_tags($campaign->description), 100) }}
  </div>

  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#05070b;">
    <tr>
      <td align="center" style="padding:40px 16px;">

        <table role="presentation" width="560" cellpadding="0" cellspacing="0" style="max-width:560px; width:100%;">

          <!-- Logo header -->
          <tr>
            <td align="center" style="padding-bottom:32px;">
              <img src="{{ asset('images/logo.jpg') }}" alt="Vyralabs" width="130" style="display:block; height:auto; border:0;">
            </td>
          </tr>

          <!-- Main card -->
          <tr>
            <td style="background-color:#0d1420; border:1px solid rgba(255,255,255,0.08); border-radius:16px; overflow:hidden;">

              @if($campaign->banner_image)
              <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                <tr>
                  <td>
                    <img src="{{ asset($campaign->banner_image) }}" alt="{{ $campaign->title }}" width="560" style="display:block; width:100%; height:auto; border:0;">
                  </td>
                </tr>
              </table>
              @endif

              <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                <tr>
                  <td style="padding:36px 32px 8px 32px;">
                    <p style="margin:0 0 10px 0; font-family:Arial, Helvetica, sans-serif; font-size:11px; font-weight:700; letter-spacing:1.5px; text-transform:uppercase; color:#22d3ee;">
                      The Longevity Letter
                    </p>
                    <h1 style="margin:0 0 14px 0; font-family:Arial, Helvetica, sans-serif; font-size:24px; line-height:1.3; font-weight:800; color:#ffffff;">
                      {{ $campaign->title }}
                    </h1>
                    <p style="margin:0 0 26px 0; font-family:Arial, Helvetica, sans-serif; font-size:14.5px; line-height:1.7; color:#94a3b8;">
                      {{ $campaign->description }}
                    </p>

                    @if($campaign->action_url)
                    <table role="presentation" cellpadding="0" cellspacing="0" style="margin-bottom:6px;">
                      <tr>
                        <td style="border-radius:10px; background-color:#22d3ee;">
                          <a href="{{ $campaign->action_url }}" target="_blank"
                             style="display:inline-block; padding:14px 30px; font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:700; color:#05070b; text-decoration:none; border-radius:10px;">
                            View Details &rarr;
                          </a>
                        </td>
                      </tr>
                    </table>
                    @endif

                  </td>
                </tr>
              </table>

              <!-- Divider -->
              <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                <tr>
                  <td style="padding:0 32px;">
                    <div style="border-top:1px solid rgba(255,255,255,0.08); height:1px; line-height:1px; font-size:0;">&nbsp;</div>
                  </td>
                </tr>
              </table>

              <!-- Biomarker chips (brand flavor, matches homepage) -->
              <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                <tr>
                  <td style="padding:24px 32px 32px 32px;">
                    <table role="presentation" cellpadding="0" cellspacing="0">
                      <tr>
                        <td style="padding:5px 12px; background-color:rgba(34,211,238,0.1); border:1px solid rgba(34,211,238,0.25); border-radius:20px; font-family:Arial, sans-serif; font-size:11px; font-weight:600; color:#22d3ee;">Vitamin D</td>
                        <td width="8"></td>
                        <td style="padding:5px 12px; background-color:rgba(245,158,11,0.1); border:1px solid rgba(245,158,11,0.25); border-radius:20px; font-family:Arial, sans-serif; font-size:11px; font-weight:600; color:#f59e0b;">Cortisol</td>
                        <td width="8"></td>
                        <td style="padding:5px 12px; background-color:rgba(52,211,153,0.1); border:1px solid rgba(52,211,153,0.25); border-radius:20px; font-family:Arial, sans-serif; font-size:11px; font-weight:600; color:#34d399;">HbA1c</td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>

            </td>
          </tr>

          <!-- Footer -->
          <tr>
            <td align="center" style="padding:28px 24px 0 24px;">
              <p style="margin:0 0 6px 0; font-family:Arial, Helvetica, sans-serif; font-size:12px; line-height:1.6; color:#475569;">
                You're receiving this because you subscribed to The Longevity Letter at Vyralabs.
              </p>
              <p style="margin:0; font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#475569;">
                <a href="{{ $unsubscribeUrl }}" style="color:#64748b; text-decoration:underline;">Unsubscribe</a>
                &nbsp;&middot;&nbsp;
                <span style="color:#475569;">&copy; {{ date('Y') }} Vyralabs</span>
              </p>
            </td>
          </tr>

        </table>

      </td>
    </tr>
  </table>

</body>
</html>