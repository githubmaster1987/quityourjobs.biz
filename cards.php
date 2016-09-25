<div class="span8">
    <div class="white-box-ab">
        <div class="span6 text-center"> 
            <h3>Open Trades</h3>
            <h2><i class="ti-arrow-up text-success"></i> <?php if (!empty($open)) {
    echo $open;
} else {
    echo '0';
} ?></h2>
            <div id="sparklinedash"><canvas style="display: inline-block; width: 144px; height: 30px; vertical-align: top;" width="144" height="30"></canvas></div>
        </div>
        <div class="span6 text-center">
            <h3>Trades Won</h3>
            <h2><i class="ti-arrow-up text-warning"></i> <?php if (!empty($won)) {
    echo $won;
} else {
    echo '0';
} ?></h2>
            <div id="sparklinedash2"><canvas style="display: inline-block; width: 144px; height: 30px; vertical-align: top;" width="144" height="30"></canvas></div>
        </div>
        <div class="clear">&nbsp;</div>
    </div>


    <div class="col-lg-6 col-sm-12 col-xs-12">
        <div class="row">
            <div class="span6 text-center">
                <div class="white-box-ab">
                    <h3>Trades Lost</h3>
                    <h2><i class="ti-arrow-up text-success"></i> <?php if (!empty($lost)) {
    echo $lost;
} else {
    echo '0';
} ?></h2>
                    <div id="sparklinedash3"><canvas style="display: inline-block; width: 144px; height: 30px; vertical-align: top;" width="144" height="30"></canvas></div>

                    <div class="clear">&nbsp;</div>
                </div>
            </div>
            <div class="span6 text-center">
                <div class="white-box-ab">
                    <h3>Lifetime Trades</h3>
                    <h2><i class="ti-arrow-up text-success"></i> <?php if (!empty($total)) {
    echo $total;
} else {
    echo '0';
} ?></h2>
                    <div id="sparklinedash4"><canvas style="display: inline-block; width: 144px; height: 30px; vertical-align: top;" width="144" height="30"></canvas></div>

                    <div class="clear">&nbsp;</div>
                </div>
            </div>


        </div>

    </div>

</div>