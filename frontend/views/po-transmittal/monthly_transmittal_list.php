<?php




$this->title = 'Transmittal List';
?>
<?= \yii\helpers\Html::csrfMetaTags() ?>
<div class="site-index " id="mainVue">

    <div class="card p-2">

        <table class="table table-hover">
            <tr class="table-danger">
                <th class="text-center" colspan="5">DVs Not Transmitted</th>
            </tr>
            <tr>
                <th>DV No.</th>
                <th>Check No.</th>
                <th>Payee</th>
                <th>Particular</th>
                <th>Gross</th>
            </tr>
            <tr v-for=" item in dvNotTransmitted">
                <td>{{item.dv_number}}</td>
                <td>{{item.check_number}}</td>
                <td>{{item.payee}}</td>
                <td>{{item.particular}}</td>
                <td class='text-right'>{{formatAmount(item.gross_amount)}}</td>
            </tr>

            <tr class="table-info">
                <th class="text-center " colspan="5">DVs Pending at RO</th>
            </tr>
            <tr>
                <th>DV No.</th>
                <th>Check No.</th>
                <th>Payee</th>
                <th>Particular</th>
                <th>Gross</th>
            </tr>
            <tr v-for=" item in dvPendingAtRo">
                <td>{{item.dv_number}}</td>
                <td>{{item.check_number}}</td>
                <td>{{item.payee}}</td>
                <td>{{item.particular}}</td>
                <td class='text-right'>{{formatAmount(item.gross_amount)}}</td>
            </tr>
            <tr class="table-success">
                <th class="text-center" colspan="5">DVs at RO</th>
            </tr>
            <tr>
                <th>DV No.</th>
                <th>Check No.</th>
                <th>Payee</th>
                <th>Particular</th>
                <th>Gross</th>
            </tr>
            <tr v-for=" item in dvAtRo">
                <td>{{item.dv_number}}</td>
                <td>{{item.check_number}}</td>
                <td>{{item.payee}}</td>
                <td>{{item.particular}}</td>
                <td class='text-right'>{{formatAmount(item.gross_amount)}}</td>
            </tr>
        </table>
    </div>

</div>

<?php


?>

<script>
    new Vue({
        el: "#mainVue",
        data: {
            transmittalData: <?= json_encode($defaultData) ?>
        },
        mounted() {
            // this.dvNotTransmitted()
        },
        methods: {
            formatAmount(amount) {
                amount = parseFloat(amount)
                if (typeof amount === 'number' && !isNaN(amount)) {
                    return amount.toLocaleString()
                }
                return 0;
            },
        },

        computed: {
            dvNotTransmitted() {
                let x = this.transmittalData.filter(item => {
                    return item.untransmitted !== null;
                })
                console.log(x)
                return x
            },
            dvAtRo() {
                return this.transmittalData.filter(item => {
                    return item.dvs_at_ro !== null;
                })
            },
            dvPendingAtRo() {
                return this.transmittalData.filter(item => {
                    return item.pending_at_ro !== null;
                })
            },
        }
    })
</script>