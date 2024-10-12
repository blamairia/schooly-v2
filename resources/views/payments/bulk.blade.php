<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Title dynamically based on the first payment in the collection -->
    <title>Reçu - {{ $payments->first()->student->first_name }} {{ $payments->first()->student->last_name }} - {{ $payments->first()->total_amount }} DZD - {{ $payments->first()->created_at->format('Ymd_His') }}</title>

    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            width: 210mm;
            height: 297mm;
            display: flex;
            flex-direction: column;
            box-sizing: border-box;
        }

        .page {
            display: flex;
            flex-direction: column;
            height: 100%;
            padding: 5mm;
            box-sizing: border-box;
        }

        .recu {
            flex: 1;
            margin-bottom: 10mm;
            padding: 5mm;
            border: 1px solid black;
            box-sizing: border-box;
        }

        .en-tete {
            text-align: center;
            margin-bottom: 5mm;
        }

        .en-tete .titre {
            font-size: 16pt;
            font-weight: bold;
        }

        .en-tete .sous-titre {
            font-size: 12pt;
        }

        .details {
            margin-bottom: 5mm;
            font-size: 10pt;
        }

        .details .ligne {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1mm;
        }

        .details .ligne div {
            flex: 1;
            text-align: right;
        }

        .details .ligne div:first-child {
            text-align: left;
        }

        .qrcode {
            text-align: center;
            margin-top: 3mm;
        }

        .pied-de-page {
            text-align: center;
            margin-top: 5mm;
            font-size: 10pt;
        }

        .pied-de-page .avis {
            font-weight: bold;
        }

        /* Watermark */
        .filigrane {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 80pt;
            color: rgba(0, 0, 0, 0.1);
            pointer-events: none;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/qrcodejs/qrcode.min.js"></script>
    <script>
        window.onload = function() {
            @foreach ($payments as $payment)
            var baseUrl = "http://127.0.0.1:8000";
            var paymentId = "{{ $payment->id }}";
            var paymentUrl = baseUrl + "/admin/payment/" + paymentId + "/change/";

            var qrcode1 = new QRCode(document.getElementById("qrcode{{ $loop->index }}-1"), {
                text: paymentUrl,
                colorDark: "#000000",
                colorLight: "#ffffff",
                width: 80,
                height: 80,
                correctLevel: QRCode.CorrectLevel.H,
            });

            var qrcode2 = new QRCode(document.getElementById("qrcode{{ $loop->index }}-2"), {
                text: paymentUrl,
                colorDark: "#000000",
                colorLight: "#ffffff",
                width: 80,
                height: 80,
                correctLevel: QRCode.CorrectLevel.H,
            });

            @endforeach
            window.print();
            setTimeout(() => {
                window.close();
            }, 500);
        }
    </script>
</head>
<body>
    @foreach ($payments as $payment)
    <div class="page">

        <!-- First Receipt -->
        <div class="recu">
            <div class="en-tete">
                <div class="sous-titre">Etablissement privé d'éducation et d'enseignement</div>
                <div class="titre">Athénée SALMI</div>

                <div class="sous-titre">Plage Rizzi Amor, 0663-66-75-32</div>
                <div class="sous-titre">Atheneesalmi23@hotmail.com</div>
                <div class="sous-titre">Reçu de Paiement</div>
            </div>
            <div class="details">
                <div class="ligne">
                    <div>ID de Paiement :</div>
                    <div>{{ $payment->id }}</div>
                </div>
                <div class="ligne">
                    <div>Étudiant :</div>
                    <div>{{ $payment->student->first_name }} {{ $payment->student->last_name }}</div>
                </div>
                <div class="ligne">
                    <div>Type de Paiement :</div>
                    <div>{{ $payment->paymentType->name }}</div>
                </div>
                <div class="ligne">
                    <div>Plan de Division :</div>
                    <div>{{ $payment->divisionPlan->name }}</div>
                </div>
                <div class="ligne">
                    <div>Partie de la Division :</div>
                    <div>Partie {{ $payment->part_number }} sur {{ $payment->divisionPlan->total_parts }}</div>
                </div>
                <div class="ligne">
                    <div>Montant Total :</div>
                    <div>DZD {{ $payment->total_amount }}</div>
                </div>
                <div class="ligne">
                    <div>Montant Payé :</div>
                    <div>DZD {{ $payment->amount_paid }}</div>
                </div>
                <div class="ligne">
                    <div>Montant Dû :</div>
                    <div>DZD {{ $payment->amount_due }}</div>
                </div>
                <div class="ligne">
                    <div>Statut :</div>
                    <div>
                        @if ($payment->status == 'pending')
                            En attente
                        @elseif ($payment->status == 'partially_paid')
                            Partiellement payé
                        @elseif ($payment->status == 'paid')
                            Payé
                        @elseif ($payment->status == 'overdue')
                            En retard
                        @else
                            {{ $payment->status }}
                        @endif
                    </div>
                </div>
                <div class="ligne">
                    <div>Méthode de Paiement :</div>
                    <div>
                        @if ($payment->payment_method == 'cash')
                            Espèces
                        @elseif ($payment->payment_method == 'tpe')
                            Tpe
                        @elseif ($payment->payment_method == 'check')
                            Chèque
                        @else
                            {{ $payment->payment_method }}
                        @endif
                    </div>
                </div>
                <div class="ligne">
                    <div>Date de Paiement :</div>
                    <div>{{ $payment->created_at->format('l, d F Y H:i:s') }}</div>
                </div>
                <div class="ligne">
                    <div>Reçu Généré À :</div>
                    <div>{{ now()->format('l, d F Y H:i:s') }}</div>
                </div>
            </div>

            <div id="qrcode{{ $loop->index }}-1" class="qrcode"></div>
            <div class="pied-de-page">
                <div class="avis">Conservez votre reçu !</div>
                <div>Ce reçu original est requis pour toute demande relative à votre paiement. Conservez-le en lieu sûr.</div>
            </div>
        </div>

        <!-- Second Receipt (Duplicate) -->
        <div class="recu">
            <div class="en-tete">
                <div class="sous-titre">Etablissement privé d'éducation et d'enseignement</div>
                <div class="titre">Athénée SALMI</div>

                <div class="sous-titre">Plage Rizzi Amor, 0663-66-75-32</div>
                <div class="sous-titre">Atheneesalmi23@hotmail.com</div>
                <div class="sous-titre">Reçu de Paiement</div>
            </div>
            <div class="details">
                <div class="ligne">
                    <div>ID de Paiement :</div>
                    <div>{{ $payment->id }}</div>
                </div>
                <div class="ligne">
                    <div>Étudiant :</div>
                    <div>{{ $payment->student->first_name }} {{ $payment->student->last_name }}</div>
                </div>
                <div class="ligne">
                    <div>Type de Paiement :</div>
                    <div>{{ $payment->paymentType->name }}</div>
                </div>
                <div class="ligne">
                    <div>Plan de Division :</div>
                    <div>{{ $payment->divisionPlan->name }}</div>
                </div>
                <div class="ligne">
                    <div>Partie de la Division :</div>
                    <div>Partie {{ $payment->part_number }} sur {{ $payment->divisionPlan->total_parts }}</div>
                </div>
                <div class="ligne">
                    <div>Montant Total :</div>
                    <div>DZD {{ $payment->total_amount }}</div>
                </div>
                <div class="ligne">
                    <div>Montant Payé :</div>
                    <div>DZD {{ $payment->amount_paid }}</div>
                </div>
                <div class="ligne">
                    <div>Montant Dû :</div>
                    <div>DZD {{ $payment->amount_due }}</div>
                </div>
                <div class="ligne">
                    <div>Statut :</div>
                    <div>
                        @if ($payment->status == 'pending')
                            En attente
                        @elseif ($payment->status == 'partially_paid')
                            Partiellement payé
                        @elseif ($payment->status == 'paid')
                            Payé
                        @elseif ($payment->status == 'overdue')
                            En retard
                        @else
                            {{ $payment->status }}
                        @endif
                    </div>
                </div>
                <div class="ligne">
                    <div>Méthode de Paiement :</div>
                    <div>
                        @if ($payment->payment_method == 'cash')
                            Espèces
                        @elseif ($payment->payment_method == 'tpe')
                            Tpe
                        @elseif ($payment->payment_method == 'check')
                            Chèque
                        @else
                            {{ $payment->payment_method }}
                        @endif
                    </div>
                </div>
                <div class="ligne">
                    <div>Date de Paiement :</div>
                    <div>{{ $payment->created_at->format('l, d F Y H:i:s') }}</div>
                </div>
                <div class="ligne">
                    <div>Reçu Généré À :</div>
                    <div>{{ now()->format('l, d F Y H:i:s') }}</div>
                </div>
            </div>

            <div id="qrcode{{ $loop->index }}-2" class="qrcode"></div>
            <div class="pied-de-page">
                <div class="avis">Conservez votre reçu !</div>
                <div>Ce reçu original est requis pour toute demande relative à votre paiement. Conservez-le en lieu sûr.</div>
            </div>
        </div>
    </div>
    <div class="page-break"></div>
    @endforeach
</body>

</html>
