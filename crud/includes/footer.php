<footer>

</footer>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>
    <!-- <script>
    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Espresso', 'Café Latte', 'Macchiato', 'Cappuccino', 'Mocha Latte'],
            datasets: [{
                label: 'Orders',
                data: [32, 64, 13, 25, 19],
                fill: false,
                borderColor: 'rgb(75, 192, 192)',
                lineTension: 0.1,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    backgroundColor: false,
                    scales: {
                        yAxes: [{
                            stacked: true
                        }]
                    }
                }
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
    </script> -->
    <script type="text/javascript">
        $(window).on('load', function() {

            setTimeout(removeLoader, 2000);

        });

        function removeLoader(){
            $('.fx').fadeOut(500, function() {
                $('.fx').remove();
            });
        }
        
        $(document).ready(function(){

            $('.container').show();

            $('#loginform').validate({
                rules: {
                    username: "required",
                    password: "required"
                },
                messages: {
                    username: "",
                    password: ""
                }
            });


            $('#loginform').bind('change keyup', function(){
                if ($('.login_username').hasClass('error') || $('.login_password').hasClass('error')) {
                    $('.login').addClass('error');
                    $('.login').removeClass('valid');
                    $('.login_username').addClass('error');
                    $('.login_password').addClass('error');
                    $('.login_username').removeClass('valid');
                    $('.login_password').removeClass('valid');
                }
                else if (!$('.login_username').val() && $('.login_password').val()) {
                    $('.login').addClass('error');
                    $('.login').removeClass('valid');
                    $('.login_username').addClass('error');
                    $('.login_password').addClass('valid');
                    $('.login_username').removeClass('valid');
                    $('.login_password').removeClass('error');
                }
                else if ($('.login_username').val() && !$('.login_password').val()) {
                    $('.login').addClass('error');
                    $('.login').removeClass('valid');
                    $('.login_username').addClass('valid');
                    $('.login_password').addClass('error');
                    $('.login_username').removeClass('error');
                    $('.login_password').removeClass('valid');
                }
                else if ($('.login_username').val() && $('.login_password').val()) {
                    $('.login').addClass('valid');
                    $('.login').removeClass('error');
                    $('.login_username').addClass('valid');
                    $('.login_password').addClass('valid');
                    $('.login_username').removeClass('error');
                    $('.login_password').removeClass('error');
                }   
                else {
                    $('.login').addClass('valid');
                    $('.login').removeClass('error');
                    $('.login_username').addClass('valid');
                    $('.login_password').addClass('valid');
                    $('.login_username').removeClass('error');
                    $('.login_password').removeClass('error');
                }
            });

            $('#loginform').bind('change keyup', function(){
                if (!$('.login_username').val() && !$('.login_password').val()) {
                    $('.login').addClass('error');
                    $('.login').removeClass('valid');
                    $('.login_username').addClass('error');
                    $('.login_password').addClass('error');
                    $('.login_username').removeClass('valid');
                    $('.login_password').removeClass('valid');
                }
                else if (!$('.login_username').val() && $('.login_password').val()) {
                    $('.login').addClass('error');
                    $('.login').removeClass('valid');
                    $('.login_username').addClass('error');
                    $('.login_password').addClass('valid');
                    $('.login_username').removeClass('valid');
                    $('.login_password').removeClass('error');
                }
                else if ($('.login_username').val() && !$('.login_password').val()) {
                    $('.login').addClass('error');
                    $('.login').removeClass('valid');
                    $('.login_username').addClass('valid');
                    $('.login_password').addClass('error');
                    $('.login_username').removeClass('error');
                    $('.login_password').removeClass('valid');
                }
                else if ($('.login_username').val() && $('.login_password').val()) {
                    $('.login').addClass('valid');
                    $('.login').removeClass('error');
                    $('.login_username').addClass('valid');
                    $('.login_password').addClass('valid');
                    $('.login_username').removeClass('error');
                    $('.login_password').removeClass('error');
                }
                else {
                    $('.login').addClass('valid');
                    $('.login').removeClass('error');
                    $('.login_username').addClass('valid');
                    $('.login_password').addClass('valid');
                    $('.login_username').removeClass('error');
                    $('.login_password').removeClass('error');
                }
            });

            // $('#loginform').submit(function(){
            //     event.preventDefault();
            //     if ($('.login_username').hasClass('error') || $('.login_password').hasClass('error')) {
            //         $('.login').addClass('error');
            //         $('.login').removeClass('valid');
            //     } else {
            //         $('.login').removeClass('error');
            //         $('.login').addClass('valid');
            //         $('.login form').addClass('success');
            //         $('#loginform input, #loginform h2').hide();
            //         $('.loader').css('display', 'block');
            //     }
            // });

        });
    </script>
        </div>
    </body>
</html>