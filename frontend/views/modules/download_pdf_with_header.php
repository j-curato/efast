<?php


/* @var $this yii\web\View */
/* @var $model app\models\Transmittal */


\yii\web\YiiAsset::register($this);
?>
<div class="transmittal-view"></div>

<?php
$baseUrl
?>
<script type="text/javascript">
    const dtiHeaderImage = new Image()
    dtiHeaderImage.src = '<?= Yii::$app->request->baseUrl . '/frontend/web/images/dti_header.png' ?>'
    const locationIcon = new Image()
    locationIcon.src = '<?= Yii::$app->request->baseUrl . '/frontend/web/images/location_icon.png' ?>'
    const globeIcon = new Image()
    globeIcon.src = '<?= Yii::$app->request->baseUrl . '/frontend/web/images/globe_icon.png' ?>'
    const mailIcon = new Image()
    mailIcon.src = '<?= Yii::$app->request->baseUrl . '/frontend/web/images/mail_icon.png' ?>'
    const phoneIcon = new Image()
    phoneIcon.src = '<?= Yii::$app->request->baseUrl . '/frontend/web/images/phone_icon.png' ?>'
    async function calculateImageAspectRatio(image,
        imageMaxWidth = 60, // Adjust as needed
        imageMaxHeight = 30,
    ) {
        const aspectRatio = image.width / image.height;
        // Set the maximum width and height
        // Adjust as needed
        // Calculate width and height based on aspect ratio and maximum dimensions

        let imageWidth = Math.min(imageMaxWidth, image.width);
        let imageHeight = imageWidth / aspectRatio;
        // If the height exceeds the maximum, resize again based on height
        if (imageHeight > imageMaxHeight) {
            imageHeight = imageMaxHeight;
            imageWidth = imageHeight * aspectRatio;
        }
        return {

            imageHeight: imageHeight,
            imageWidth: imageWidth
        }
    }

    function displayTexts(doc, data, height, operator) {
        let addTextFinalHeight = 0
        $.each(data, function(key, val, y) {
            let yAxis = height
            if (operator == '+') {
                yAxis += (5 * key)
            }
            if (operator == '-') {
                yAxis -= (5 * key)
            }
            if (val.fontStyle) {
                doc.setFont(undefined, val.fontStyle)
            }
            doc.text(val.value, val.width ?? 14, yAxis)
            if (val.fontStyle) {
                doc.setFont(undefined, 'normal')
            }
            addTextFinalHeight = yAxis
        })
        return addTextFinalHeight
    }

    function getPdfPageCenter(doc, text, pageWidth) {
        let fontSize = doc.getFontSize()
        var textWidth = doc.getStringUnitWidth(text) * fontSize / doc.internal.scaleFactor;
        return (pageWidth - textWidth) / 2;
    }
    async function generatePDF() {
        $('.pdf-export td').css('font-size', '10px');
        $('.pdf-export td').css('padding', '4px');
        $('.pdf-export th').css('font-size', '10px');
        $('.pdf-export th').css('padding', '4px');
        const {
            jsPDF
        } = window.jspdf;
        const doc = new jsPDF();
        var totalPagesExp = '{total_pages_count_string}'

        const headerImageAspectRatio = await calculateImageAspectRatio(dtiHeaderImage)
        var pageSize = doc.internal.pageSize
        var pageWidth = pageSize.width ? pageSize.width : pageSize.getWidth()
        doc.setFontSize(9)
        const textHeaderDefaultWidth = 14
        let headerTexts = [{
                value: '<?= $date ?>',
            },
            {
                value: '',
            },
            {
                value: 'ADA JUNE M.HORMILLADA',
                fontStyle: 'bold',
            },
            {
                value: 'State Auditor III',
            },
            {

                value: 'OIC - Audit Team Leader',
            },
            {

                value: 'COA - DTI Caraga',
            },
            {
                value: '',

            },
            {

                value: 'Dear Ma’am Hormillada:',
            },
            {
                value: '',
            },
            {

                value: '    We are hereby submitting the following DVs, with assigned Transmittal # <?= $serial_number; ?> of DTI Regional Office:',
            },
        ]
        let addTextFinalHeight = displayTexts(doc, headerTexts, headerImageAspectRatio.imageHeight + 20, '+')

        // return

        function addTexts() {}
        // get aspect ratio of location image
        const locationIconAspectRatio = await calculateImageAspectRatio(locationIcon, 4, 4)
        const globeIconAspectRatio = await calculateImageAspectRatio(locationIcon, 4, 4)
        const mailIconAspectRatio = await calculateImageAspectRatio(locationIcon, 4, 4)
        const phoneIconAspectRatio = await calculateImageAspectRatio(locationIcon, 4, 4)
        doc.autoTable({
            useCss: true,
            startY: addTextFinalHeight + 5,
            showHead: 'everyPage',
            margin: {
                top: 46,
                bottom: 35
            },
            styles: {
                fontSize: number = 8,
            },
            // body: [],
            html: ".pdf-export",
            willDrawPage: function(data) {
                // Header
                doc.setFontSize(20)
                doc.setTextColor(40)
                // if (base64Img) {
                doc.addImage(dtiHeaderImage, 'PNG', data.settings.margin.left, 10, headerImageAspectRatio.imageWidth, headerImageAspectRatio.imageHeight)
                // }
                // doc.text('Report', data.settings.margin.left + 15, 22)
            },
            didDrawPage: function(data) {

                doc.setFontSize(9)
                var pageSize = doc.internal.pageSize
                var pageHeight = pageSize.height ? pageSize.height : pageSize.getHeight()
                doc.setTextColor(0, 123, 255);
                doc.text('REGION 13 (Caraga)', getPdfPageCenter(doc, 'REGION 13 (Caraga)', pageWidth), pageHeight - 30)
                doc.setTextColor(0, 0, 0);
                doc.text('Certified ISO 9001:2015', getPdfPageCenter(doc, 'Certified ISO 9001:2015', pageWidth), pageHeight - 25)
                doc.setFontSize(8)
                doc.text("DTI-Caraga Regional Office, WEst Wing, 3rd Floor, D&V Plaza Building,", data.settings.margin.left, pageHeight - 15)
                doc.text("(085) 816-3136", pageWidth - 80, pageHeight - 15)
                doc.text("J.C. Aquino Avenue, Butuan City Philippines", data.settings.margin.left, pageHeight - 10)
                doc.setTextColor(0, 123, 255);
                doc.textWithLink('www.dti.gov.ph/caraga',
                    pageWidth - 80,
                    pageHeight - 10, {
                        url: '',
                        font: 'times',
                        fontSize: 8,
                        underlined: {
                            color: [0, 0, 0],
                            lineWidth: 0.1,
                            dash: {
                                length: 1
                            }
                        },
                        linkCallback: function(url) {}
                    });
                doc.textWithLink('caraga@dti.gov.ph',
                    pageWidth - 40,
                    pageHeight - 10, {
                        url: '',
                        font: 'times',
                        fontSize: 8,
                        underlined: {
                            color: [0, 0, 0],
                            lineWidth: 0.1,
                            dash: {
                                length: 1
                            }
                        },
                        linkCallback: function(url) {}
                    });

                doc.addImage(locationIcon, 'PNG', data.settings.margin.left - 5, pageHeight - 18.5, locationIconAspectRatio.imageWidth, locationIconAspectRatio.imageHeight)
                doc.addImage(globeIcon, 'PNG', pageWidth - 85, pageHeight - 13, globeIconAspectRatio.imageWidth, globeIconAspectRatio.imageHeight)
                doc.addImage(mailIcon, 'PNG', pageWidth - 45, pageHeight - 13, mailIconAspectRatio.imageWidth, mailIconAspectRatio.imageHeight)
                doc.addImage(phoneIcon, 'PNG', pageWidth - 85, pageHeight - 18.5, phoneIconAspectRatio.imageWidth, phoneIconAspectRatio.imageHeight)


            },

        })

        // Total page number plugin only available in jspdf v1.0+
        if (typeof doc.putTotalPages === 'function') {
            doc.putTotalPages(totalPagesExp)
        }
        doc.save("newFile.pdf");
        $('.pdf-export td').css('font-size', '16px');
        $('.pdf-export td').css('padding', '10px');
        $('.pdf-export th').css('font-size', '16px');
        $('.pdf-export th').css('padding', '10px');
    }
</script>