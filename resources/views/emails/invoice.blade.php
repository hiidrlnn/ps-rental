<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>

<body style="margin:0; padding:0; background:#0f172a; font-family:Arial, sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0">
<tr>
<td align="center">

    <!-- CONTAINER -->
    <table width="600" cellpadding="0" cellspacing="0" style="background:#111827; border-radius:12px; overflow:hidden; margin:30px 0;">

        <!-- HEADER -->
        <tr>
            <td style="background:#1e293b; padding:20px;">
                <h2 style="margin:0; color:#38bdf8;">Rental PS</h2>
                <p style="margin:5px 0 0; color:#94a3b8; font-size:13px;">
                    Invoice Pembayaran
                </p>
            </td>
        </tr>

        <!-- BODY -->
        <tr>
            <td style="padding:25px; color:#e5e7eb;">

                <p style="margin:0 0 10px;">
                    Halo <b>{{ $rental->customer->name ?? 'Pelanggan' }}</b>,
                </p>

                <p style="color:#94a3b8;">
                    Pembayaran telah berhasil diproses.
                </p>

                <!-- INFO UTAMA -->
                <table width="100%" style="margin-top:20px; background:#1f2937; border-radius:10px; padding:15px;">
                    <tr>
                        <td style="padding:6px 0; color:#9ca3af;">No Rental</td>
                        <td style="padding:6px 0; text-align:right;">#{{ $rental->id }}</td>
                    </tr>
                    <tr>
                        <td style="padding:6px 0; color:#9ca3af;">Unit</td>
                        <td style="padding:6px 0; text-align:right;">
                            {{ $rental->unit->name ?? '-' }} ({{ $rental->unit->type ?? '-' }})
                        </td>
                    </tr>
                </table>

                @php $type = $type ?? 'rental'; @endphp

                <!-- DETAIL -->
                <table width="100%" style="margin-top:20px;">

                    @if($type === 'rental')

                        <tr>
                            <td colspan="2" style="padding-bottom:10px; font-weight:bold;">
                                Detail Penyewaan
                            </td>
                        </tr>

                        <tr>
                            <td style="color:#9ca3af;">Jumlah Unit</td>
                            <td style="text-align:right;">
                                {{ $rental->quantity }} unit
                            </td>
                        </tr>

                        <tr>
                            <td style="color:#9ca3af;">Durasi</td>
                            <td style="text-align:right;">
                                {{ $rental->duration_days }} hari
                            </td>
                        </tr>

                        <tr>
                            <td style="color:#9ca3af;">Harga / Unit</td>
                            <td style="text-align:right;">
                                Rp {{ number_format($rental->unit->price_per_day ?? 0, 0, ',', '.') }}
                            </td>
                        </tr>

                        <tr>
                            <td style="color:#9ca3af;">Tanggal Sewa</td>
                            <td style="text-align:right;">
                                {{ \Carbon\Carbon::parse($rental->rental_date)->format('d M Y, H:i') }}
                            </td>
                        </tr>

                        <tr>
                            <td style="color:#9ca3af;">Tanggal Kembali</td>
                            <td style="text-align:right;">
                                {{ \Carbon\Carbon::parse($rental->return_date)->format('d M Y, H:i') }}
                            </td>
                        </tr>

                        <tr>
                            <td style="color:#9ca3af;">Total</td>
                            <td style="text-align:right; color:#38bdf8; font-weight:bold;">
                                Rp {{ number_format($rental->total_price ?? 0, 0, ',', '.') }}
                            </td>
                        </tr>

                    @else

                        <tr>
                            <td colspan="2" style="padding-bottom:10px; font-weight:bold; color:#f87171;">
                                Pembayaran Denda
                            </td>
                        </tr>

                        <tr>
                            <td style="color:#9ca3af;">Tanggal Harus Kembali</td>
                            <td style="text-align:right;">
                                {{ \Carbon\Carbon::parse($rental->return_date)->format('d M Y, H:i') }}
                            </td>
                        </tr>

                        <tr>
                            <td style="color:#9ca3af;">Tanggal Kembali Aktual</td>
                            <td style="text-align:right;">
                                {{ \Carbon\Carbon::parse($rental->actual_return_time)->format('d M Y, H:i') }}
                            </td>
                        </tr>

                        <tr>
                            <td style="color:#9ca3af;">Total Denda</td>
                            <td style="text-align:right; color:#f87171; font-weight:bold;">
                                Rp {{ number_format($rental->fine_amount ?? 0, 0, ',', '.') }}
                            </td>
                        </tr>

                    @endif

                </table>

                <!-- PEMBAYARAN -->
                <table width="100%" style="margin-top:25px; border-top:1px solid #374151; padding-top:15px;">

                    <tr>
                        <td style="color:#9ca3af;">Metode Pembayaran</td>
                        <td style="text-align:right;">
                            {{ ucfirst($payment->method ?? '-') }}
                        </td>
                    </tr>

                    <tr>
                        <td style="color:#9ca3af;">Jumlah Dibayar</td>
                        <td style="text-align:right;">
                            Rp {{ number_format($payment->amount_paid ?? 0, 0, ',', '.') }}
                        </td>
                    </tr>

                    <tr>
                        <td style="color:#9ca3af;">Kembalian</td>
                        <td style="text-align:right;">
                            Rp {{ number_format($payment->change_amount ?? 0, 0, ',', '.') }}
                        </td>
                    </tr>

                    <tr>
                        <td style="color:#9ca3af;">Status</td>
                        <td style="text-align:right;">
                            <span style="background:#22c55e; color:#fff; padding:4px 10px; border-radius:5px; font-size:12px;">
                                LUNAS
                            </span>
                        </td>
                    </tr>

                </table>

                <!-- FOOT NOTE -->
                <p style="margin-top:25px; color:#9ca3af; font-size:13px;">
                    Terima kasih telah menggunakan layanan Rental PS.
                </p>

            </td>
        </tr>

        <!-- FOOTER -->
        <tr>
            <td style="background:#020617; padding:15px; text-align:center; color:#64748b; font-size:12px;">
                © {{ date('Y') }} Rental PS
            </td>
        </tr>

    </table>

</td>
</tr>
</table>

</body>
</html>