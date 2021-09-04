<div id="paypal-button-container"></div>
<script>
    var merchantID = "78JE4WP39BFXU";
    var clientID = "ATXPmGBi407yTPL9r-K6M6jj6IyyuPwrmWreuVZgmDdR6sDHpRUmBAGZFO6HwUhzOpCpprprKecrfE1q";
    var url = "https://www.paypal.com/sdk/js?client-id=" + clientID +
        "&merchant-id=" + merchantID + "&disable-funding=credit,card&currency=RUB&locale=en_US";
</script>
<script src="https://www.paypal.com/sdk/js?client-id=ATXPmGBi407yTPL9r-K6M6jj6IyyuPwrmWreuVZgmDdR6sDHpRUmBAGZFO6HwUhzOpCpprprKecrfE1q&merchant-id=JHEDJP4DGDRRY&disable-funding=credit,card&currency=USD&locale=en_US"></script>
<script>
    paypal.Buttons({
        createOrder: function(data, actions) {
            return actions.order.create({
                purchase_units: [{
                    amount: {
                        value: '10'
                    }
                }],
                payment_instruction: {
                    platform_fees: [{
                        amount: {
                            value: "5"
                        }
                    }]
                }
            });
        },
        onApprove: function(data, actions) {
            return actions.order.capture().then(function(details) {
                console.log(details);
            })
        },
        onCancel: function(data) {
            console.log("uff");
        },
        onError: (error) => {
            console.log('error', error);
        }
    }).render('#paypal-button-container');
</script>