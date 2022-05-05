<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<style>
        body {
            font-family: Arial;
        }

        .coupon {
            border: 5px dotted #bbb;
            width: 70%;
            border-radius: 15px;
            margin: 0 auto;
            max-width: 550px;
        }

        .container {
            padding: 2px 16px;
            background-color: #f1f1f1;
        }

        .promo {
            background: #ccc;
            padding: 3px;
        }

        .expire {
            color: red;
        }
        p.code {
            text-align: center;
            font-size: 20px;
        }
        p.expire {
            text-align: center;
        }
        h2.note {
            text-align: center;
            font-size: large;
            text-decoration: underline;
        }
    </style>
</head>
<body>

	<div class="coupon">

		<div class="container">
			<h3>Mã khuyến mãi từ shop 
                <a target="_blank" href="http://localhost:8080/websiteNoiThat/">
                    https://websiteNoiThat.herokuapp.com
                </a>
			</h3>
		</div>
		<div class="container" style="background-color:white; text-align: justify;">

			<h2 class="note"><b><i>
				@if($coupon['coupon_condition']==1)
					Giảm {{$coupon['coupon_num']}}%
				@else
					Giảm {{number_format($coupon['coupon_num'],0,',','.')}}đ
				@endif
			    cho tổng đơn hàng đặt mua</i></b></h2> 

			<p>Quý khách đã từng mua hàng tại shop 
                <a target="_blank" style="color:red" href="http://localhost:8080/websiteNoiThat/">
                    https://websiteNoiThat.herokuapp.com
                </a> 
                nếu đã có tài khoản xin vui lòng 
                
                <a target="_blank" style="color:red" href="http://localhost:8080/websiteNoiThat/dang-nhap">
                đăng nhập</a> 
                vào tài khoản để mua hàng và nhập mã code phía dưới để được giảm giá mua hàng, xin cảm ơn quý khách.
                Chúc quý khách thật nhiều sức khỏe và bình an trong cuộc sống. </p>
		</div>
		<div class="container">
			<p class="code">Sử dụng Code sau: <span class="promo">{{$coupon['coupon_code']}}</span> </p>
            <p class="code">Chỉ có {{$coupon['coupon_qty']}} mã giảm giá, nhanh tay kẻo hết.</p>
			<p class="expire">Mã giảm giá có hạn sử dụng đến : {{$coupon['end_coupon']}}</p>
		</div>

	</div>

</body>
</html> 
