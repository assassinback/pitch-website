<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Checkout - </title>

    
    
        <link rel="stylesheet" href="https://ajax.aspnetcdn.com/ajax/bootstrap/3.3.7/css/bootstrap.min.css" />
<meta name="x-stylesheet-fallback-test" content="" class="sr-only" /><script>!function(a,b,c,d){var e,f=document,g=f.getElementsByTagName("SCRIPT"),h=g[g.length-1].previousElementSibling,i=f.defaultView&&f.defaultView.getComputedStyle?f.defaultView.getComputedStyle(h):h.currentStyle;if(i&&i[a]!==b)for(e=0;e<c.length;e++)f.write('<link href="'+c[e]+'" '+d+"/>")}("position","absolute",["\/lib\/bootstrap\/dist\/css\/bootstrap.min.css"], "rel=\u0022stylesheet\u0022 ");</script>
        <link rel="stylesheet" href="/css/site.min.css?v=fs2QbVo2HxNvoNNuy7-ZJTJ3CopK99qzshBB-k1rS_s" />
    
</head>
<body>
    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
        </div>
    </nav>
    <div class="container body-content">
        

<!DOCTYPE html>
<html>
<head>
    <script>

        /* Style being applied to the iframe */

        var customStyle = {

            // All the elements in the iframe
            // Position are from field + label

            iframe: {
                showCardTypeIcons: true,
                useTranslations: false,
                backgroundColor: '#f5f5f5',
                layout: 'compact',
                styles: {},
                errorFieldId: 'errors',

            }
        };
    </script>
    <meta charset="UTF-8">
    
    
    
        <script src="https://additionsjs.judopay.com/releases/v0.1.312/judopay.min.js"></script>
    
</head>
<body>
    <br />
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="panel panel-default">
            <div class="panel-heading" style="background-color: #e6e6e6">
                <h3 class="panel-title">
                    Card Details
                </h3>
            </div>
            <div class="panel-body" style="background-color: #f5f5f5">

                <form role="form" action="/Checkout/Pay" method="post" id="payment-form">
                    <div id="payment-iframe">
                    </div>
                    <!-- Used to display form errors -->
                    <button id="submit-payment-button" name="submit-payment" class="btn col-xs-8 col-xs-offset-2" style='font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;font-size: 16px;font-weight: bold;background-color: #ffbe0f; box-shadow:0 2px 0 0 #b3850b'>
                        Pay
                    </button>
                </form>
            </div>
        </div>
        <div id="errors" class="judopay-errors" style="height: 38px">
        </div>
    </div>
</div>
    
<div id="languages" class="btn col-xs-8 col-xs-offset-2">
    <div id="en" class="flag flag-gb" onclick="javascript: judo.changeIframeLanguage('en');"></div>
    <div id="es" class="flag flag-es" onclick="javascript: judo.changeIframeLanguage('es');"></div>
    <div id="fr" class="flag flag-fr" onclick="javascript: judo.changeIframeLanguage('fr');"></div>
    <div id="de" class="flag flag-de" onclick="javascript: judo.changeIframeLanguage('de');"></div>
    <div id="pt" class="flag flag-pt" onclick="javascript: judo.changeIframeLanguage('pt');"></div>
</div>
</body>
</html>
<script>
    var judo = new JudoPay('2hdFclZ3IzumACTs', true);
    var payment = judo.createCardDetails('payment-iframe', customStyle.iframe);

    // Handle form submission
    var form = document.getElementById('payment-form');

    function returnHandler(oneUseToken, clientDetails) {
        // Insert the token ID into the form so it gets submitted to the server
        var form = document.getElementById('payment-form');
        var hiddenInput = document.createElement('input');
        hiddenInput.setAttribute('type', 'hidden');
        hiddenInput.setAttribute('name', 'oneUseToken');
        hiddenInput.setAttribute('value', oneUseToken);

        form.appendChild(hiddenInput);

        var deviceInput = document.createElement('input');
        deviceInput.setAttribute('type', 'hidden');
        deviceInput.setAttribute('name', 'deviceId');
        deviceInput.setAttribute('value', clientDetails);

        form.appendChild(deviceInput);

        // Submit the form
        form.submit();
    }

    function errorHandler(result) {
        // Inform the user if there was an error
        // You will want to report on this
        var errorElement = document.getElementsByClassName('judopay-errors')[0];
		if (errorElement === null) return;
        errorElement.textContent = result.error.message;
    }

    form.addEventListener('submit', function (event) {
        event.preventDefault();

        judo.createToken(payment)
            .then(function (result) {
                console.log(result)
                if (result.error) {
                    errorHandler(result);
                } else {
                    // Send the one use token to your server to be used
                    //returnHandler(result.oneUseToken, result.clientDetails);
                }
            })
            .catch(function (result) {
                errorHandler(result);
            });
    });
</script>

    </div>

    
    
        <script src="https://ajax.aspnetcdn.com/ajax/jquery/jquery-2.2.0.min.js" crossorigin="anonymous" integrity="sha384-K+ctZQ+LL8q6tP7I94W+qzQsfRV2a+AfHIi9k8z8l9ggpc8X+Ytst4yBo/hH+8Fk">
        </script>
<script>(window.jQuery||document.write("\u003Cscript src=\u0022\/lib\/jquery\/dist\/jquery.min.js\u0022 crossorigin=\u0022anonymous\u0022 integrity=\u0022sha384-K\u002BctZQ\u002BLL8q6tP7I94W\u002BqzQsfRV2a\u002BAfHIi9k8z8l9ggpc8X\u002BYtst4yBo\/hH\u002B8Fk\u0022\u003E\u003C\/script\u003E"));</script>
        <script src="https://ajax.aspnetcdn.com/ajax/bootstrap/3.3.7/bootstrap.min.js" crossorigin="anonymous" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa">
        </script>
<script>(window.jQuery && window.jQuery.fn && window.jQuery.fn.modal||document.write("\u003Cscript src=\u0022\/lib\/bootstrap\/dist\/js\/bootstrap.min.js\u0022 crossorigin=\u0022anonymous\u0022 integrity=\u0022sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa\u0022\u003E\u003C\/script\u003E"));</script>
        <script src="/js/site.min.js?v=47DEQpj8HBSa-_TImW-5JCeuQeRkm5NMpJWZG3hSuFU"></script>
    

    
</body>
</html>
