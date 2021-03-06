<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cart extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	public $company_token;

    public $url;
    public $ptl;
    public $ptl_type;
    public $currency;


	public function __construct(){
		parent::__construct();
		
        $this->load->library('cart');
        $this->load->model('cms_model');
        $this->load->model('packages_model');
        $this->load->model('settings_model');
        $this->load->model('Cart_model');
        $this->load->helper('text');
        $this->load->library('email');
        



        /*******DPO CREDENTIALS************/
        $this->company_token = "9F416C11-127B-4DE2-AC7F-D5710E4C5E0A";
        // $this->url = 'https://secure1.sandbox.directpay.online';
        //$this->url = 'https://secure.3gdirectpay.com';
        $this->url = 'https://secure.3gdirectpay.com';
        $this->ptl = '5';
        $this->ptl_type = 'minutes';
        $this->cancelURL = base_url() . 'cart/dpocancel';
        $this->returnURL = base_url() . 'cart/dporeturn';
        $this->currency = 'USD';
        /*******DPO CREDENTIALS************/
		
	}


	public function index()
	{
		
		if($this->input->post('url1') == 'packages_details')
		{
			if($this->session->userdata('id') == '')
			{

				$data = array(

				'id' => $this->input->post('prod'),

				'name' => $this->input->post('pName'),

				'price' => $this->input->post('offer'),

				'qty' => $this->input->post('qty'),

				'stock' => $this->input->post('stock'),

				'pCode' => $this->input->post('code'),

				'cat_id' => $this->input->post('cat'),

				'image' => $this->input->post('image'),

				'max' => $this->input->post('max'),

				'AdateP' => $this->input->post('date'),

				'details' => 'Package',

				'user_id' => 0

				

				);
			}
			else
			{

				$data = array(

				'id' => $this->input->post('prod'),

				'name' => $this->input->post('pName'),

				'price' => $this->input->post('offer'),

				'qty' => $this->input->post('qty'),

				'stock' => $this->input->post('stock'),

				'pCode' => $this->input->post('code'),

				'cat_id' => $this->input->post('cat'),

				'image' => $this->input->post('image'),

				'max' => $this->input->post('max'),

				'AdateP' => $this->input->post('date'),

				'details' => 'Package',

				'user_id' => $this->session->userdata('id'),

				);
			}
		}
		else
		{
			if($this->session->userdata('id') == '')
			{
				// $this->cart->destroy();
				$data = array(

				'id' => $this->input->post('id'),

				'name' => $this->input->post('name'),

				'price' => $this->input->post('price'),

				'qty' => $this->input->post('qty'),

				'stock' => $this->input->post('stock'),

				'max' => $this->input->post('max'),

				'AdateP' => $this->input->post('date'),

				'cat_id' => $this->input->post('cat_id'),

				'image' => $this->input->post('image'),

				'time' => $this->input->post('time'),

				'details' => 'Activity',

				'user_id' => 0

				);
			}
			else
			{

				$data = array(

				'id' => $this->input->post('id'),

				'name' => $this->input->post('name'),

				'price' => $this->input->post('price'),

				'qty' => $this->input->post('qty'),

				'stock' => $this->input->post('stock'),

				'max' => $this->input->post('max'),

				'AdateP' => $this->input->post('date'),

				'cat_id' => $this->input->post('cat_id'),

				'image' => $this->input->post('image'),

				'time' => $this->input->post('time'),

				'details' => 'Activity',

				'user_id' => $this->session->userdata('id')

				);
			}
		}



		$this->session->set_userdata('pId', $data['id']);
		$row_id = md5($data['id']);
		$this->session->set_userdata('Id', $row_id);
		
		
		
        $cart = $this->cart->get_item($row_id);
        $total = $cart['qty'] + $data['qty'];

			if($total<=$data['max'] && $total<=$data['stock'])
			{
			
					$this->cart->insert($data);
					
			
			}
		echo $this->view();

		
	}


	public function busket()
	{
		echo $this->view();
	}


	


	public function view()
	{
		$output = '';
		$output .='
		
		<div class="column-labels">
            
            <label class="product-image">Name</label>
            <label class="product-details">Booking Date</label>
            <label class="product-price">Unit Price</label>
            <label class="product-quantity">Quantity</label>
            <label class="product-removal">Remove</label>
            <label class="product-line-price">Total</label>
        </div>
        <input type="hidden" name="count" id="package_count" value="'. count($this->cart->contents()).'">';
        
     
        $count = 0;
        foreach ($this->cart->contents() as $value) {
        	# code...
        	$count++;
        	
        	$output .= '
               
        <input type="hidden" name="catid" value="'.$this->session->set_userdata('ID', $value['cat_id']).'" class="catid" id="catid">
        <input type="hidden" name="stock" value="'.$value['stock'].'" class="stock" id="stocky">
        <input type="hidden" name="cartquantity" value="'.$value['qty'].'" class="cartqty" id="cartquantity-'.$value['rowid'].'">
        


		<div class="product">
		
            <div class="product-image">
              <div class="product-title">'.$value['name'].'</div>
              
            </div>
            <div class="product-details">'.$value['AdateP'].'</div>
            <div class="product-price">USD$ '.number_format($value['price'],2).'</div>
            <div class="product-quantity">';
            if($value['details']== "Package")
            {
            	$output .= '<span style="color : #192d3d; text-align : center;">Package</span>';
            }
            else
            {
            	$output .= '<span style="color : #192d3d; text-align : center;">Activity</span>';
            }
            $output .=  '<form id="myform" method="POST" action="#" style="text-align : left !important;">
                  
                  <input type="button" value="-" class="qtyminus" field="quantity" id="'.$value['rowid'].'" data-rowid="'.$value['rowid'].'" data-price="'.$value['price'].'"/>
                  
                  <input type="text" name="quantity" value="'.$value['qty'].'" class="qty" id="quantity-'.$value['rowid'].'" disabled/>

                  <input type="hidden" name="maxquantity" value="'.$value['max'].'" class="max" id="maxquantity-'.$value['rowid'].'" readonly/>
                  
                  <input type="button" value="+" class="qtyplus" field="quantity" id="'.$value['rowid'].'" data-rowid="'.$value['rowid'].'" data-price="'.$value['price'].'"/>
              </form>
            </div>
            <div class="product-removal">
              <button class="remove-product"  id="'.$value['rowid'].'">
                Remove
              </button>
            </div>
            <div class="product-line-price" id="newval">USD$ '.number_format($value['subtotal'],2).'</div>
            
            </div>
            ';
        }

        $output .= '
            <div class="totals clearfix">            
            <div class="totals-item totals-item-total">
              <label>Grand Total</label>
              <div class="totals-value" id="cart-total">USD$ '.number_format($this->cart->total(),2).'</div>
            </div>
          </div>
              <div class="action_btn clearfix">

                <button class="checkout btn_secondary" onclick="shopping_con()" style="float: left;"> Continue Shopping</button>

                <button class="checkout btn_primary" onclick="check()">Checkout</button>
              
              </div>';



              if($count==0)
              {
              	$output = "<h3 align='center'>Cart is Empty</h3>";

              }
              return $output;
       

	}


	


	public function remove()
	{
		$delid = $this->input->post('id');
		$data = array(
				'rowid' => $delid,
				'qty'   => 0
		);
		$this->cart->update($data);
		echo $this->view();
	}


	public function update()
	{
		
		$id = $this->input->post('id');
		$qty = $this->input->post('qty');
		$price = $this->input->post('price');

		$data = array(
				'rowid' => $id,
				'qty'   => $qty,
				'subtotal' => $price
		);
		$this->cart->update($data);
		echo $this->view();
		
	}


	public function delete()
	{
		$this->cart->destroy();
	}



	public function emailCheck()
	{
		$emailId = $this->input->post('email');
		$where = ['email' => $emailId];
		$email = $this->Cart_model->emailFetch($where);
		echo $email;
	}



	public function sendEmail()
	{
		$data = array(
			'name'  => $this->input->post('name'),
			'email' => $this->input->post('email'),
			'comm'  => $this->input->post('comm'),
			'prod'  => $this->input->post('product')
		);

		$from= $data['email'];
		//$to ="testdevloper007@gmail.com";
		$to ="arijit.dutta48@gmail.com";
		
		
		$subject = 'Enquiry For: '.$data['prod']; 
		$message="<p>Hi Admin, <br>" .$data['name']."
		<br> Massege: ".$data['comm'].
		"<br> Email: ".$data['email']."</p><br><br> Regards,<br>" .$data['name'];

		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: '.$from."\r\n".
			'Reply-To: '.$from."\r\n" .
			'X-Mailer: PHP/' . phpversion();

		@mail($to, $subject, $message, $headers);
								
	}



	public function login()
	{
		if($this->session->userdata('id') == '')
		{
			$cat_id = base64_decode($this->uri->segment(2));
			$content_data = array();
			$holiday = $this->cms_model->about_fetchRecord();
			$content_data['holiday'] = $holiday;
			$contact = $this->settings_model->contact_fetchRecord();
			$content_data['contact'] = $contact;
			$cat = $this->packages_model->getcatlist();
			$catId = $cat[0]['id'];
			$wher = ['category<>'=> $catId];
			$deal = $this->packages_model->sidebarFetch($wher);
	    	$content_data['deal'] = $deal;
			$this->load->view('login', $content_data);
		}
		else
		{
			redirect('profile');
		}
	}


	public function loginAccess()
	{


		if($this->session->userdata('id') == '')
		{
				$userName = $this->input->post('username');
				$passWord = md5($this->input->post('password'));
				$where = ['email' => $userName, 'pass' => $passWord];
				$login = $this->Cart_model->userFetch($where);
				
				
				if($login)
				{
					$wheree = ['account' => $login];
					$accountDetails = $this->Cart_model->accFetch($wheree);
					$where1 = ['id' => $login];
					$accName = $this->Cart_model->accName($where1);
					
					$newdata = array(
						   'id'    => $login,
		                   'name'  => $accountDetails[0]['nm'],
		                   'email' => $accountDetails[0]['em'],
		                   'add1'  => $accountDetails[0]['addr1'],
		                   'add2'  => $accountDetails[0]['addr2'],
		                   'add3'  => $accountDetails[0]['addr3'],
		                   'add4'  => $accountDetails[0]['addr4'],
		                   'add5'  => $accountDetails[0]['addr5'],
		                   'add6'  => $accountDetails[0]['addr6']
		                   
		               );

				$this->session->set_userdata($newdata);
				$this->session->set_userdata('accName', $accName[0]['name']);
				}
				else
				{
					// $this->utility->setMsg('Username/Password Not Matched', 'ERROR');
					$this->session->set_flashdata('err_msg', 'Username/Password Not Matched');

					redirect('cart/login');
				}

			

		}
		
					$content_data = array();
					$about = $this->cms_model->about_fetchRecord();
					$content_data['holiday'] = $about;
					$contact = $this->settings_model->contact_fetchRecord();
					$content_data['contact'] = $contact;
					$cat = $this->packages_model->getcatlist();
					$catId = $cat[0]['id'];
					$wher = ['category<>'=> $catId];
					$deal = $this->packages_model->sidebarFetch($wher);
			    	$content_data['deal'] = $deal;
			    	$where = ['enCountry'=> 'yes'];
			    	$country = $this->Cart_model->getlist($where);
			    	$content_data['country'] = $country;
			    	$content_data['cart'] = $this->cart->total();
			    	
					$delid = $this->session->userdata('Id');
						$data = array(
						'rowid' => $delid,
						'user_id' => $this->session->userdata('id')
						);
					$this->cart->update($data);

					$curr = $this->packages_model->currFetch();
					$content_data['curr'] = $curr;
	    	
			    	$this->load->view('billing_details', $content_data);
		
		
			
			
	}


	public function codeCheck()
	{
		$code  = $this->input->post('code');
		$total = $this->cart->total();
		$date  = date("Y-m-d");
		$where = ['cDiscountCode'=> $code];
		$data1  = $this->Cart_model->couponCheck($where);
		$data  = $this->Cart_model->couponCheck1($where);
		$catId = $this->input->post('cID');
		$whereUsage = ['account'=>$this->session->userdata('id'), 'cDiscountCode' => $code];
		$usage_code = $this->Cart_model->usageFetch($whereUsage);
		$count = count($usage_code);
		
		if($this->session->userdata('id') != '')
		{
			if($data1 == 1)
			{
				$last = substr($data[0]['cDiscount'], -1);
				$explode = explode(',', $data[0]['categories']);
				if(($data[0]['cExpiry'] > $date)==true  && $total>$data[0]['cMin']  && $data[0]['cLive']== 'yes')
				{
					if(in_array($catId, $explode))
					{
						echo 3;
					}
					else
					{
						if($count < $data[0]['cUsage'] || $data[0]['cUsage']==0)
						{
							if($last == '%')
							{
								$Amount = $total * ($data[0]['cDiscount']/100);
								if($data[0]['cMin'] > 0)
								{
									if($Amount <= $data[0]['cMin'])
									{
										$sub = $Amount;
										$totalAmount = $total - $sub;
									}
									else
									{
										$sub = $data[0]['cMin'];
										$totalAmount = $total - $sub;
									}
								}
								else
								{
									$sub = $Amount;
									$totalAmount = $total - $sub;
								}
							}
							else
							{
								$totalAmount = $total - $data[0]['cDiscount'];
							}

							$output = '
								<input type="hidden" name="carttotal" value="'.number_format((float)$this->cart->total(), 2, '.', '').'" class="carttotal" id="carttotal">';
							if($last == '%')
							{
							$output .= '<input type="hidden" name="cartdiscount" value="'.number_format((float)$sub, 2, '.', '').'" class="cartdiscount" id="cartdiscount">
								<input type="hidden" name="cartdiscountcode" value="'.$code.'" class="cartdiscountcode" id="cartdiscountcode">';
							}
							else
							{
							$output .= '<input type="hidden" name="cartdiscount" value="'.number_format((float)$data[0]['cDiscount'], 2, '.', '').'" class="cartdiscount" id="cartdiscount">
								<input type="hidden" name="cartdiscountcode" value="'.$code.'" class="cartdiscountcode" id="cartdiscountcode">';
							}
							$output .= '<input type="hidden" name="amountpay" value="'.number_format((float)$totalAmount, 2, '.', '').'" class="amountpay" id="amountpay">';
							echo $output;
						}
						else
						{
							echo 5;
						}
					}
				}
				else
				{
					echo 2;
				}
			}
			else
			{
				echo 1;
			}
		}
		else
		{
			echo 4;
		}

	}


	public function codeRemove()
	{
		$code  = $this->input->post('code');
		$catId  = $this->input->post('catId');
		$accId  = $this->input->post('accId');
		$total = $this->cart->total();
		$date  = date("Y-m-d");

		$output = '<input type="hidden" name="carttotal" value="'.number_format((float)$this->cart->total(), 2, '.', '').'" class="carttotal1" id="carttotal1">';
								
		$output .= '<input type="hidden" name="amountpay" value="'.number_format((float)$totalAmount, 2, '.', '').'" class="amountpay1" id="amountpay1">';
		echo $output;
	}



	public function billing()
	{
		$this->session->unset_userdata('id');
		$this->session->unset_userdata('name');
		$this->session->unset_userdata('email');
		$this->session->unset_userdata('add1');
		$this->session->unset_userdata('add2');
		$this->session->unset_userdata('add3');
		$this->session->unset_userdata('add4');
		$this->session->unset_userdata('add5');
		$this->session->unset_userdata('add6');

		$content_data = array();
		$about = $this->cms_model->about_fetchRecord();
		$content_data['holiday'] = $about;
		$contact = $this->settings_model->contact_fetchRecord();
		$content_data['contact'] = $contact;
		$cat = $this->packages_model->getcatlist();
		$catId = $cat[0]['id'];
		$wher = ['category<>'=> $catId];
		$deal = $this->packages_model->sidebarFetch($wher);
    	$content_data['deal'] = $deal;
    	$where = ['enCountry'=> 'yes'];
    	$country = $this->Cart_model->getlist($where);
    	$content_data['country'] = $country;
    	$content_data['cart'] = $this->cart->total();
    	$ProdId = $this->session->userdata('pId');
    	$prdwhere = ['id' => $ProdId];
    	$conRestrict = $this->Cart_model->conFetch($prdwhere);
    	
    	if(count($this->cart->contents())>0)
    	{
    		$this->load->view('billing_details', $content_data);
    	}
    	else
    	{
    		redirect('home');
    	}
	}

	

	public function finalPayment()
	{
		$subTotal = $this->input->post('subTotal');
		$discount = $this->input->post('discount');
		$totalPay = $this->input->post('totalPay');
		$code     = $this->input->post('discode');
		$curr = $this->packages_model->currFetch();

        	
			$output = '<div class="alert alert-danger" id="errorpay" style="display: none;">
                <p><strong>Warning</strong> <span id="errormsgpay">lorem ipsum doler sit amet</span></p>
              </div>
              <div class="col6">
                <div class="form_group" id="payment">
                
                <select name="paymentoption" id="paymentoption" class="form_control paymentoption">
                   <option value="DPO" selected>DPO</option>
                   <option value="paylater">Pay Later</option>
                </select>
              </div>

              </div>

              <div class="table_responsive">
                <table class="table">
				  <thead>
                     <tr>
                        <td>Sub Total</td>';
                        if(!empty($discount))
              			{
               $output .='<td>Discount Price</td>';
                    	}
               $output .='<td>Amount To Pay</td>
                     </tr>
				  </thead>

				  <tbody>';
			   foreach ($curr as $val1) {
				  	
               $output .=    
               		'<tr>
                       <td>USD$&nbsp;'.number_format($this->cart->total(), 2).'&nbsp;&nbsp;(&nbsp;'.currency(number_format($this->cart->total(), 2), $val1['curr_rate'], $val1['curr_name']).'&nbsp;)</td>';
                       if(!empty($discount))
              			{
                $output .='<td>USD$&nbsp;<span id="dis">'.$discount.'</span>&nbsp;&nbsp;(&nbsp;'.currency($discount, $val1['curr_rate'], $val1['curr_name']).'&nbsp;)</td>
                   		
                       	 <td>USD$&nbsp;<span class="ATP">'.$totalPay.'</span>&nbsp;&nbsp;(&nbsp;'.currency($total, $val1['curr_rate'], $val1['curr_name']).'&nbsp;)</td>';
                       	}
                       	else
                       	{
                $output .='<td>USD$&nbsp;<span class="ATP">'.number_format($this->cart->total(), 2).'</span>&nbsp;&nbsp;(&nbsp;'.currency(number_format($this->cart->total(), 2), $val1['curr_rate'], $val1['curr_name']).'&nbsp;)</td>';
                       	}
                $output .='</tr>';
            	}
				$output .='</tbody>
                </table>'; 
                // $count = 0;
	            $cartId = $cartcId = $cartQty = $cartStock = $cartPrice = $cartsPrice = $cartDetails = array();
	             
	          
	        	foreach ($this->cart->contents() as $value) {
	        	// $count++;
	        		$cartId[]      = $value['id'];
	        		$cartcId[]     = $value['cat_id'];
	        		$cartQty[]     = $value['qty'];
	        		$cartStock[]   = $value['stock'];
	        		$cartPrice[]   = $value['price'];
	        		$cartsPrice[]  = $value['subtotal'];
	        		$cartDetails[] = $value['details'];
	        		$cartBDate[]   = $value['AdateP'];
	        	
				}
				$nId      = implode(',',$cartId);
				$ncId     = implode(',',$cartcId);
				$nQty     = implode(',',$cartQty);
				$nStock   = implode(',',$cartStock);
				$nPrice   = implode(',',$cartPrice);
				$nsPrice  = implode(',',$cartsPrice);
				$nDetails = implode(',',$cartDetails);
				$nBDate   = implode(',',$cartBDate);
				$output .= '
						<input type="hidden" name="proid" value="'.$nId.'" class="proid" id="proid">
						<input type="hidden" name="catid1" value="'.$ncId.'" class="catid1" id="catid1">
						<input type="hidden" name="qty1" value="'.$nQty.'" class="qty1" id="qty1">
						<input type="hidden" name="stock" value="'.$nStock.'" class="stock" id="stock">
						<input type="hidden" name="price" value="'.$nPrice.'" class="indprice" id="indprice">
						<input type="hidden" name="subprice" value="'.$nsPrice.'" class="subprice" id="subprice">
						<input type="hidden" name="details" value="'.$nDetails.'" class="details" id="details">
						<input type="hidden" name="AdateP" value="'.$nBDate.'" class="AdateP" id="AdateP">';

				
          		
			echo $output;
	}



	public function finalPayment1()
	{
		$subTotal = $this->input->post('subTotal');
		$totalPay = $this->input->post('totalPay');
		

        	
			$output = '<div class="alert alert-danger" id="errorpay" style="display: none;">
                <p><strong>Warning</strong> <span id="errormsgpay">lorem ipsum doler sit amet</span></p>
              </div>
              <div class="col6">
                <div class="form_group" id="payment">
                
                <select name="paymentoption" id="paymentoption" class="form_control paymentoption">
                   <option value="bank">Banking</option>
                   <option value="cash on delivery">Cash On Delivery</option>
                   
                </select>
              </div>

              </div>

              <div class="table_responsive">
                <table class="table">
				  <thead>
                     <tr>
                        <td>Sub Total</td>';
                        
               $output .='<td>Discount Price</td>';
                    	
               $output .='<td>Amount To Pay</td>
                     </tr>
				  </thead>
				  <tbody>
                    <tr>
                       <td>$'.number_format((float)$this->cart->total(), 2, '.', '').'</td>';
                       
                $output .='<td>$<span class="ATP">'.number_format((float)$this->cart->total(), 2, '.', '').'</span></td>';
                       	
                $output .='</tr>
				  </tbody>
                </table>'; 
              $count = 0;
	            // $cart = array();
	        	foreach ($this->cart->contents() as $value) {
	        	$count++;
	        	$output .= '
						<input type="hidden" name="proid" value="'.$value['id'].'" class="proid" id="proid">
						<input type="hidden" name="catid1" value="'.$value['cat_id'].'" class="catid1" id="catid1">
						<input type="hidden" name="qty1" value="'.$value['qty'].'" class="qty1" id="qty1">
						<input type="hidden" name="stock" value="'.$value['stock'].'" class="stock" id="stock">
						<input type="hidden" name="price" value="'.$value['price'].'" class="indprice" id="indprice">
						<input type="hidden" name="AdateP" value="'.$value['AdateP'].'" class="AdateP" id="AdateP">';
				}
          		
		
			echo $output;
	}


	public function insert()
	{
		$countrywhere = ['id' => $this->input->post('b_ccode')];
		$country = $this->packages_model->countryFetch($countrywhere); 
		
		if(!empty($this->input->post('discount')))
		{
		$data = array(
				'$b_name'    => $this->input->post('b_name'),
		        '$b_email'   => $this->input->post('b_email'),
		        '$b_ccode'   => $this->input->post('b_ccode'),
		        '$b_address' => $this->input->post('b_address'),
		        '$b_add'     => $this->input->post('b_add'),
		        '$b_town'    => $this->input->post('b_town'),
		        '$b_state'   => $this->input->post('b_state'),
		        '$b_post'    => $this->input->post('b_post'),
		        '$account'   => $this->input->post('account'),
		        '$note'      => $this->input->post('note'),
		        '$prodId'    => $this->input->post('prodId'),
		        '$catId'     => $this->input->post('catId'),
		        '$quantity'  => $this->input->post('quantity'),
		        '$stock'     => $this->input->post('stock'),
		        '$subPrice'  => $this->input->post('subPrice'),
		        '$details'   => $this->input->post('details'),
		        '$total'     => $this->input->post('total'),
		        '$insur'     => 0,
		        '$gtotal'    => $this->input->post('total'),
		        '$date'      => date("Y-m-d"),
		        '$time'      => date("h:i:s"),
		        '$method'    => $this->input->post('method'),
		        '$price'     => $this->input->post('price'),
		        '$discount'	 => $this->input->post('discount'),
		        '$discode'	 => $this->input->post('discode'),
		        '$AdateP'	 => $this->input->post('AdateP')
		    );
		}
		else
		{
			$data = array(
				'$b_name'    => $this->input->post('b_name'),
		        '$b_email'   => $this->input->post('b_email'),
		        '$b_ccode'   => $this->input->post('b_ccode'),
		        '$b_address' => $this->input->post('b_address'),
		        '$b_add'     => $this->input->post('b_add'),
		        '$b_town'    => $this->input->post('b_town'),
		        '$b_state'   => $this->input->post('b_state'),
		        '$b_post'    => $this->input->post('b_post'),
		        '$account'   => $this->input->post('account'),
		        '$note'      => $this->input->post('note'),
		        '$prodId'    => $this->input->post('prodId'),
		        '$catId'     => $this->input->post('catId'),
		        '$quantity'  => $this->input->post('quantity'),
		        '$stock'     => $this->input->post('stock'),
		        '$subPrice'  => $this->input->post('subPrice'),
		        '$details'   => $this->input->post('details'),
		        '$total'     => $this->input->post('total'),
		        '$insur'     => 0,
		        '$gtotal'    => $this->input->post('total'),
		        '$date'      => date("Y-m-d"),
		        '$time'      => date("h:i:s"),
		        '$method'    => $this->input->post('method'),
		        '$price'     => $this->input->post('price'),
		        '$discount'	 => 0,
		        '$discode'	 => '',
		        '$AdateP'	 => $this->input->post('AdateP')
		    );
		}

		$this->session->set_userdata('data', $data);

		$sales     = $this->Cart_model->fetch();

		$invoiceId = $sales[0]['id'] + 1;


		if($data['$account'] == 1)
			{
		//////////////////Account Register//////////////////
					
					$registerData = array(

						'name' 			=> $data['$b_name'], 
						'created' 		=> $data['$date'], 
						'email'			=> $data['$b_email'], 
						'pass'			=> md5($data['$b_name']), 
						'enabled'		=> 'yes', 
						'verified'		=> 'yes', 
						'timezone'		=> 0, 
						'ip'			=> $this->input->ip_address(), 
						'notes'			=> '', 
						'system1'		=> '', 
						'system2'		=> '', 
						'language'		=> 'english', 
						'currency'		=> '', 
						'enablelog'		=> 'yes', 
						'newsletter'	=> 'yes', 
						'type'			=> 'personal', 
						'tradediscount'	=> '', 
						'minqty'		=> '', 
						'maxqty'		=> 0, 
						'stocklevel'	=> '', 
						'mincheckout'	=> round(0.00,2), 
						'trackcode'		=> '', 
						'recent'		=> ''
					);

					$registerId = $this->Cart_model->registerInsert($registerData);

	   ///////////////////////Registration Mail Sent/////////////////

				$from= "arijit.dutta48@gmail.com";
				//$to ="testdevloper007@gmail.com";
				$to = $data['$b_email'];
				
				
				$subject = 'Heritage Registration Details'; 
				$message="<p>Hi, <br>" .$data['$b_name']."
				<br> Massege: Thank You For Registration
				<br> Username : ".$data['$b_email'].
				";<br>Password : ".$data['$b_name'].
				"<br><br> Regards,<br> Heritage";

				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headers .= 'From: '.$from."\r\n".
					'Reply-To: '.$from."\r\n" .
					'X-Mailer: PHP/' . phpversion();

				@mail($to, $subject, $message, $headers);

				    
		//////////////////Sale Insert/////////////////////    				
					$saleData = array(
							 'invoiceNo'          => $invoiceId,
							 'account'	          => $registerId, 
							 'saleNotes'          => $data['$note'], 
							 'bill_1'	          => $data['$b_name'], 
							 'bill_2'	          => $data['$b_email'], 
							 'bill_3'             => $data['$b_address'], 
							 'bill_4'             => $data['$b_add'], 
							 'bill_5'             => $data['$b_town'], 
							 'bill_6'             => $data['$b_state'], 
							 'bill_7'             => $data['$b_post'], 
							 'bill_8'             => '', 
							 'bill_9'             => $data['$b_ccode'], 
							 'ship_1'             => '', 
							 'ship_2'             => '', 
							 'ship_3'             => '', 
							 'ship_4'             => '',   
							 'ship_5'             => '', 
							 'ship_6'             => '', 
							 'ship_7'             => '', 
							 'ship_8'             => '', 
							 'buyerAddress'       => '', 
							 'paymentStatus'      => 'shipping', 
							 'gatewayID'          => '', 
							 'taxPaid'            => round(0.00,2), 
							 'taxRate'            => 0, 
							 'couponCode'         => $data['$discode'], 
							 'couponTotal'        => $data['$discount'], 
							 'codeType'           => '', 
							 'subTotal'           => $data['$total'], 
							 'grandTotal'         => $data['$gtotal'], 
							 'shipTotal'          => '', 
							 'globalTotal'        => round(0.00,2), 
							 'insuranceTotal'     => $data['$insur'], 
							 'chargeTotal'        => round(0.00,2), 
							 'globalDiscount'     => 0, 
							 'manualDiscount'     => round(0.00,2), 
							 'isPickup'           => 'no', 
							 'shipSetCountry'     => '', 
							 'shipSetArea'        => '', 
							 'setShipRateID'      => 0, 
							 'shipType'           => 'weight', 
							 'cartWeight'         => 0, 
							 'purchaseDate'       => $data['$date'], 
							 'purchaseTime'       => $data['$time'], 
							 'buyCode'            => md5($invoiceId), 
							 'saleConfirmation'   => 'yes', 
							 'paymentMethod'      => $data['$method'], 
							 'ipAddress'          => $this->input->ip_address(), 
							 'restrictCount'      => 0, 
							 'zipLimit'           => 0, 
							 'downloadLock'       => 'no', 
							 'optInNewsletter'    => 'yes', 
							 'paypalErrorTrigger' => 0, 
							 'trackcode'          => '', 
							 'type'               => 'personal', 
							 'wishlist'           => 0, 
							 'platform'           => 'desktop' 
					);

					$saleInsertId = $this->Cart_model->saleInsert($saleData);

					$this->session->set_userdata('saleInsertId', $saleInsertId);
					$prodWhere1 = ['id' => $data['$prodId']];
					$prodDetails1 = $this->Cart_model->prevQty($prodWhere1);


					
				//////////////////Status Insert/////////////////////
					
					$statusData = array(

							'saleID'		=> $saleInsertId, 
							'statusNotes'	=> 'Order Placed on Website', 
							'dateAdded'		=> $data['$date'], 
							'timeAdded'		=> $data['$time'], 
							'orderStatus'	=> 'shipping', 
							'adminUser'		=> 'system', 	
							'visacc'		=> 'no', 
							'account'		=> $registerId
					);

					$this->Cart_model->statusInsert($statusData);

				//////////////////Purchase Insert/////////////////////
					$exId      = explode(',',$data['$prodId']);
					$excId     = explode(',',$data['$catId']);
					$exQty     = explode(',',$data['$quantity']);
					$exStock   = explode(',',$data['$stock']);
					$exPrice   = explode(',',$data['$price']);
					$exsPrice  = explode(',',$data['$subPrice']);
					$exDetails = explode(',',$data['$details']);
					$exAdateP  = explode(',',$data['$AdateP']);
					// echo $count = count($exId);exit;
					for($i=0; $i<count($exId); $i++)
					{

					$purchaseData = array(

						'purchaseDate'      => $data['$date'], 
						'purchaseTime'      => $data['$time'], 
						'saleID'            => $saleInsertId, 
						'productType'       => 'virtual', 
						'type'				=> $exDetails[$i],
						'productID'			=> $exId[$i], 
						'giftID'			=> 0, 
						'categoryID'		=> $excId[$i], 
						'salePrice'			=> $data['$total'],
						'booking_date' 		=> $exAdateP[$i],
						'liveDownload'		=> 'no', 
						'persPrice'			=> $exsPrice[$i], 
						'attrPrice'			=> $exPrice[$i], 
						'insPrice'			=> 0, 
						'globalDiscount'	=> 0, 
						'globalCost'		=> 0, 
						'productQty'		=> $exQty[$i], 
						'productWeight'		=> 0, 
						'downloadAmount'	=> 0, 
						'downloadCode'		=> '', 
						'buyCode'			=> md5($saleInsertId), 
						'saleConfirmation'	=> 'yes', 
						'deletedProductName'=> '', 
						'freeShipping'		=> 'no', 
						'wishpur'			=> 0, 
						'platform'			=> 'desktop'
					);

					$this->Cart_model->purchaseInsert($purchaseData);
					}
				//////////////////Address Book Insert/////////////////////

					$regAddressData = array(

						'account'			=> $registerId, 
						'nm'				=> $data['$b_name'], 
						'em'				=> $data['$b_email'], 
						'addr1'				=> $data['$b_ccode'], 
						'addr2'				=> $data['$b_address'], 
						'addr3'				=> $data['$b_add'], 
						'addr4'				=> $data['$b_town'], 
						'addr5'				=> $data['$b_state'], 
						'addr6'				=> $data['$b_post'], 
						'addr7'				=> '', 
						'addr8'				=> '', 
						'zone'				=> 0
					);

					$this->session->set_userdata('regAddressData', $regAddressData);

					$this->Cart_model->addressBookInsert($regAddressData);

			//////////////////Discount Insert/////////////////////

				$discountData = array(

					'cCampaign'		=> '', 
					'cDiscountCode' => $data['$discode'], 
					'cUseDate'		=> $data['$date'], 
					'saleID'		=> $saleInsertId, 
					'discountValue' => $data['$discount']
				);
				if(!empty($data['$discount']) && !empty($data['$discode']))
				{

					$this->Cart_model->discountInsert($discountData);

				}

			///////////////////////Update Product Qty/////////////////////

				for($i=0; $i<count($exId); $i++)
				{
					if($exDetails[$i] == 'Package')
					{
						$whereQty = ['id' => $exId[$i]];
						$updateQty = $exStock[$i] - $exQty[$i];
						$prodUpdate = array('pStock' => $updateQty);
						$this->Cart_model->prodUpdate($prodUpdate, $whereQty);
					}
					
				}


/**********************************DPO PAYMENT*****************************/
				if($this->input->post('method')=='DPO')
				{
					$order_total = $this->input->post('total');
	                $billing_first_name = $this->input->post('b_name');
	                $billing_last_name = $this->input->post('b_name');
	               // $billing_phone = $this->clean($user_details['user_contact']);
	                $billing_email = $this->input->post('b_email');
	                $billing_address_1 = $this->input->post('b_address');
	                
	                $billing_postcode = $this->input->post('b_post');

	                $billing_country = $country[0]['cISO_2'];

	                $param = array(
	                    'order_id'            => md5($saleInsertId),
	                    'amount'              =>'<PaymentAmount>'.$order_total.'</PaymentAmount>',
	                    'first_name'          =>'<customerFirstName>'.$billing_first_name.'</customerFirstName>' ,
	                    'last_name'           =>'<customerLastName>'.$billing_last_name.'</customerLastName>',
	                   
	                    'email'               =>'<customerEmail>'.$billing_email.'</customerEmail>',
	                    'address'             =>'<customerAddress>'.$billing_address_1.'</customerAddress>',
	                   
	                    'zipcode'             =>'<customerZip>'.$billing_postcode.'</customerZip>',
	                    'country'             =>'<customerCountry>'.$billing_country.'</customerCountry>',
	                    'ptl_type'            =>($this->ptl_type == 'minutes')? '<PTLtype>minutes</PTLtype>' : "",
	                    'ptl'                 => (!empty($this->ptl))? '<PTL>'.$this->ptl.'</PTL>' : "",
	                    'currency'            => $this->currency
	                );

	                //pr($param);

	                
	                $response =  $this->create_send_xml_request($param);
	                
	                if ($response === FALSE){

	                    echo '1|Payment error: Unable to connect to the payment gateway, please try again';
	                }else{

	                    //convert the XML result into array
	                    $xml = new SimpleXMLElement($response);
	                    if ($xml->Result[0] != '000') {
	                        echo '1|Payment error code: '.$xml->Result[0]. ', '.$xml->ResultExplanation[0];
	                    }

	       

	                    $paymnetURL = $this->url."/payv2.php?ID=".$xml->TransToken[0];
	                    echo '2|'.$paymnetURL;
	                    //redirect($paymnetURL);
	                }
	 /**********************************DPO*****************************/   
	            }
	            else
	            {

            	$transaction = array(

	                    	 'transaction_id'      => 0,
	                    	 'booking_id'          => 'BOOK_'.date("Ymdhis"),
	                    	 'user_id'		       => $regAddressData['account'],
	                    	 'sale_id'			   => $saleInsertId,
	                    	 'payment_status'      => 0,
	                    	 'Is_package'	       => 0, 
	                    	 'Is_activity'	       => 0, 
	                    	 'transaction_amount'  => $data['$gtotal'], 
	                    	 'discounted_amount'   => $data['$discount'], 
	                    	 'tranaction_type'     => 1, 
	                    	 'payment_type'        => 2, 
	                    	 'trans_date'          => date("Y-m-d h:i:s"), 
							 'reservation_status'  => 0, 
	                    	 'is_lr_used'          => 0, 
	                    	 'is_lr_gained'        => 0.00, 
	                    	 'addon'               => '', 
	                    	 'checkin_checkout'    => 0, 
	                    	 'confirmation_text'   => ''

	                    );
                    $trnasId = $this->packages_model->transInsert($transaction);

                    $where = ['saleID' => $saleInsertId];
					$purdetails = $this->packages_model->orderFetch3($where);
					foreach ($purdetails as $purvalue) 
                    {
                    	if($purvalue['type']=='Package')
                      {
                      	$trnswhere = ['holiday_transaction_id' => $trnasId];
                      	$transactionUpd = array('Is_package'=> 1);
                      	$this->packages_model->trnasUpdate($transactionUpd, $trnswhere);
                      }
                      else
                      {
                      	$trnswhere = ['holiday_transaction_id' => $trnasId];
                      	$transactionUpd = array('Is_activity'=> 1);
                      	$this->packages_model->trnasUpdate($transactionUpd, $trnswhere);
                      }
                  	}

         ///////////////////////////Admin Email Sent///////////////////

				$from= 'no-reply@gamil.com';
				//$to ="testdevloper007@gmail.com";
				$to ="arijit.dutta48@gmail.com";
				
				
				$subject = 'Purchase Package And Activity Details';
				$message = "";
				$message .="<img src='".base_url('assets/images/logo.png')."' alt='' height='42' width='42' align='left'><br><br> 
				<p>Hi Admin,<br>
				<h2>".$data['$b_name']." Payment Status Is Unpaid</h2>
				<h3>".$data['$b_name']." Booking ID is: ".$transaction['booking_id'];
				$purwhere = ['saleID' => $saleInsertId];
            	$Pdetails = $this->packages_model->orderFetch1($purwhere);
            	$count = count($Pdetails);
            	if($count>0)
		        {
				$message .=
				"<br><h3>Package Details:</h3><br> 
					<table style='border: 1px solid black; border-collapse: collapse;'>
						<thead>
	                        <tr>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Package Name</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Package Category</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Package Price</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Package Quantity</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Total</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Package Booking Date</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Package Notes</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Package Buy Code</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Package Date</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Package Time</th>

	                            
	                        </tr>
                    	</thead>
                    	<tbody>";
                	foreach ($Pdetails as $value) 
			        {
			          $total = $value['productQty']*$value['pPrice'];
                $message .=
                			"<tr>
	                    		<td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['pName']."</td>
		                        <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['catname']."</td>
		                        <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>$".$value['pPrice']."</td>
		                        <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['productQty']."</td>
		                        <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$total."</td>
		                        <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['booking_date']."</td>";
		                    
		                        if($data['$note'] != '')
		                        {
		         					$message .="<td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$data['$note']."</td>";
		                    	}
		                    	else
		                    	{
		                    	  $message .="<td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>N/A</td>";
		                    	}
		        $message .= 
								"<td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".md5($saleInsertId)."</td>
		                        <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$data['$date']."</td>
		                        <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$data['$time']."</td>
		                        
                        
                    		</tr>";
                    		}
                $message .= 	
                		"<tbody>
                    </table><br><br>";
                }
                $Adetails = $this->packages_model->orderFetch2($purwhere);
	            $count1 = count($Adetails);
		        if($count1>0)
		        {
                $message .=
                "<h3>Activity Details:</h3><br>

                    <table style='border: 1px solid black; border-collapse: collapse;'>
	                   <thead>
	                   <tr>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Activity Name</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Activity Category</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Activity Price</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Activity Quantity</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Total</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Activity Booking Date</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Activity Notes</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Activity Buy Code</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Activity Date</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Activity Time</th>
	                            
	                        </tr>
	                    </thead>
	                    <tbody>";
	            foreach ($Adetails as $value) 
		        {
                   $total1 = $value['productQty']*$value['activity_price'];
	           $message .=
	                        "<tr>
                          
	                            <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['activity_name']."</td>
	                            <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['theme_name']."</td>
	                            <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>$".$value['activity_price']."</td>
	                            <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['productQty']."</td>
	                            <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$total1."</td>
	                            <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['booking_date']."</td>";
		                    
		                        if($data['$note'] != '')
		                        {
		         					$message .="<td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$data['$note']."</td>";
		                    	}
		                    	else
		                    	{
		                    	  $message .="<td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>N/A</td>";
		                    	}
		       $message .= 
								"<td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".md5($saleInsertId)."</td>
	                            <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['purchaseDate']."</td>
		                        <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['purchaseTime']."</td>
	                            
                            
                        	</tr>";
                        }                      
                $message .=
               			 "</tbody>
                    </table>";
                }
                    if($data['$discount']<0)
                    {
						$message .=
						"<br><h3>Discount Code: ".$data['$discode']."</h3>
						 <br><h3>Discount Price: $".$data['$discount']."</h3>";
					}
				$message .= "<h3>Total Purchase Price: $".$data['$gtotal']."</h3>
				<h3>Buyer Details:</h3><br>
				<table style='border: 1px solid black; border-collapse: collapse;'>
						<thead>
	                        <tr>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Buyer Name:</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Buyer Address:</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Buyer Email</th>
	                        </tr>
                    	</thead>
                    	<tbody>
                    		<tr>
	                    		<td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$data['$b_name']."</td>
		                        <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$data['$b_address'].',&nbsp;'.$data['$b_add'].',&nbsp;'.$data['$b_town'].',&nbsp;'.$data['$b_state'].',&nbsp;'.$data['$b_post']."</td>
		                        <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$data['$b_email']."</td>
                        
                    		</tr>
                    	<tbody>
                    </table><br><br>
				
				Regards,<br><h5>Heritage</h5>";

				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headers .= 'From: '.$from."\r\n".
					'Reply-To: '.$from."\r\n" .
					'X-Mailer: PHP/' . phpversion();

				@mail($to, $subject, $message, $headers);


		///////////////////////////Buyer Email Sent///////////////////

				$from= "arijit.dutta48@gmail.com";
				//$to ="testdevloper007@gmail.com";
				$to = $data['$b_email'];
				
				
				$subject = 'Purchase Package And Activity Details'; 
				$message = "";
				$message .="<img src='".base_url('assets/images/logo.png')."' alt='' height='42' width='42' align='left'><br><br> 
				<p>Hi ".$data['$b_name'].",<br>
				<h2>Your Payment Status Is Unpaid</h2>
				<h3>Your Booking ID is: ".$transaction['booking_id'];
				$purwhere = ['saleID' => $saleInsertId];
            	$Pdetails = $this->packages_model->orderFetch1($purwhere);
            	$count = count($Pdetails);
            	if($count>0)
		        {
				$message .=
				"<br><h3>Package Details:</h3><br> 
					<table style='border: 1px solid black; border-collapse: collapse;'>
						<thead>
	                        <tr>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Package Name</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Package Category</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Package Price</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Package Quantity</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Total</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Package Booking Date</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Package Notes</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Package Buy Code</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Package Date</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Package Time</th>

	                            
	                        </tr>
                    	</thead>
                    	<tbody>";
                	foreach ($Pdetails as $value) 
			        {
			          $total = $value['productQty']*$value['pPrice'];
                $message .=
                			"<tr>
	                    		<td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['pName']."</td>
		                        <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['catname']."</td>
		                        <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>$".$value['pPrice']."</td>
		                        <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['productQty']."</td>
		                        <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$total."</td>
		                        <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['booking_date']."</td>";
		                    
		                        if($data['$note'] != '')
		                        {
		         					$message .="<td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$data['$note']."</td>";
		                    	}
		                    	else
		                    	{
		                    	  $message .="<td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>N/A</td>";
		                    	}
		        $message .= 
								"<td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".md5($saleInsertId)."</td>
		                        <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$data['$date']."</td>
		                        <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$data['$time']."</td>
		                        
                        
                    		</tr>";
                    		}
                $message .= 	
                		"<tbody>
                    </table><br><br>";
                }
                $Adetails = $this->packages_model->orderFetch2($purwhere);
	            $count1 = count($Adetails);
		        if($count1>0)
		        {
                $message .=
                "<h3>Activity Details:</h3><br>

                    <table style='border: 1px solid black; border-collapse: collapse;'>
	                   <thead>
	                   <tr>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Activity Name</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Activity Category</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Activity Price</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Activity Quantity</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Total</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Activity Booking Date</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Activity Notes</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Activity Buy Code</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Activity Date</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Activity Time</th>
	                            
	                        </tr>
	                    </thead>
	                    <tbody>";
	            foreach ($Adetails as $value) 
		        {
                   $total1 = $value['productQty']*$value['activity_price'];
	           $message .=
	                        "<tr>
                          
	                            <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['activity_name']."</td>
	                            <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['theme_name']."</td>
	                            <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>$".$value['activity_price']."</td>
	                            <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['productQty']."</td>
	                            <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$total1."</td>
	                            <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['booking_date']."</td>";
		                    
		                        if($data['$note'] != '')
		                        {
		         					$message .="<td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$data['$note']."</td>";
		                    	}
		                    	else
		                    	{
		                    	  $message .="<td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>N/A</td>";
		                    	}
		       $message .= 
								"<td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".md5($saleInsertId)."</td>
	                            <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['purchaseDate']."</td>
		                        <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['purchaseTime']."</td>
	                            
                            
                        	</tr>";
                        }                      
                $message .=
               			 "</tbody>
                    </table>";
                }
                    if($data['$discount']<0)
                    {
						$message .=
						"<br><h3>Discount Code: ".$data['$discode']."</h3>
						 <br><h3>Discount Price: $".$data['$discount']."</h3>";
					}
				$message .= "<h3>Total Purchase Price: $".$data['$gtotal']."</h3>				
				Regards,<br><h5>Heritage</h5>";

				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headers .= 'From: '.$from."\r\n".
					'Reply-To: '.$from."\r\n" .
					'X-Mailer: PHP/' . phpversion();

				@mail($to, $subject, $message, $headers);

                  	$this->cart->destroy();
			
				}
				
			}
			else
			{

		//////////////////Sale Insert/////////////////////

			$saleData = array(
					 'invoiceNo'          => $invoiceId,
					 'account'	          => $data['$account'], 
					 'saleNotes'          => $data['$note'], 
					 'bill_1'	          => $data['$b_name'], 
					 'bill_2'	          => $data['$b_email'], 
					 'bill_3'             => $data['$b_address'], 
					 'bill_4'             => $data['$b_add'], 
					 'bill_5'             => $data['$b_town'], 
					 'bill_6'             => $data['$b_state'], 
					 'bill_7'             => $data['$b_post'], 
					 'bill_8'             => '', 
					 'bill_9'             => $data['$b_ccode'], 
					 'ship_1'             => '', 
					 'ship_2'             => '', 
					 'ship_3'             => '', 
					 'ship_4'             => '',   
					 'ship_5'             => '', 
					 'ship_6'             => '', 
					 'ship_7'             => '', 
					 'ship_8'             => '', 
					 'buyerAddress'       => '', 
					 'paymentStatus'      => 'shipping', 
					 'gatewayID'          => '', 
					 'taxPaid'            => round(0.00,2), 
					 'taxRate'            => 0, 
					 'couponCode'         => $data['$discode'], 
					 'couponTotal'        => $data['$discount'], 
					 'codeType'           => '', 
					 'subTotal'           => $data['$total'], 
					 'grandTotal'         => $data['$gtotal'], 
					 'shipTotal'          => '', 
					 'globalTotal'        => round(0.00,2), 
					 'insuranceTotal'     => $data['$insur'], 
					 'chargeTotal'        => round(0.00,2), 
					 'globalDiscount'     => 0, 
					 'manualDiscount'     => round(0.00,2), 
					 'isPickup'           => 'no', 
					 'shipSetCountry'     => '', 
					 'shipSetArea'        => '', 
					 'setShipRateID'      => 0, 
					 'shipType'           => 'weight', 
					 'cartWeight'         => 0, 
					 'purchaseDate'       => $data['$date'], 
					 'purchaseTime'       => $data['$time'], 
					 'buyCode'            => md5($invoiceId), 
					 'saleConfirmation'   => 'yes', 
					 'paymentMethod'      => $data['$method'], 
					 'ipAddress'          => $this->input->ip_address(), 
					 'restrictCount'      => 0, 
					 'zipLimit'           => 0, 
					 'downloadLock'       => 'no', 
					 'optInNewsletter'    => 'yes', 
					 'paypalErrorTrigger' => 0, 
					 'trackcode'          => '', 
					 'type'               => 'personal', 
					 'wishlist'           => 0, 
					 'platform'           => 'desktop' 
			);
			$saleInsertId = $this->Cart_model->saleInsert($saleData);
			$this->session->set_userdata('saleInsertId', $saleInsertId);
			$prodWhere = ['id' => $data['$prodId']];
			$prodDetails = $this->Cart_model->prevQty($prodWhere);



			//////////////////Status Insert/////////////////////
				
				$statusData = array(

						'saleID'		=> $saleInsertId, 
						'statusNotes'	=> 'Order Placed on Website', 
						'dateAdded'		=> $data['$date'], 
						'timeAdded'		=> $data['$time'], 
						'orderStatus'	=> 'shipping', 
						'adminUser'		=> 'system', 	
						'visacc'		=> 'no', 
						'account'		=> $data['$account']
				);
				$this->Cart_model->statusInsert($statusData);

			//////////////////Purchase Insert/////////////////////
				$exId      = explode(',',$data['$prodId']);
				$excId     = explode(',',$data['$catId']);
				$exQty     = explode(',',$data['$quantity']);
				$exStock   = explode(',',$data['$stock']);
				$exPrice   = explode(',',$data['$price']);
				$exsPrice  = explode(',',$data['$subPrice']);
				$exDetails = explode(',',$data['$details']);
				$exAdateP  = explode(',',$data['$AdateP']);
				// echo $count = count($exId);exit;
				for($i=0; $i<count($exId); $i++)
				{
					$purchaseData = array(

						'purchaseDate'      => $data['$date'], 
						'purchaseTime'      => $data['$time'], 
						'saleID'            => $saleInsertId, 
						'productType'       => 'virtual', 
						'type'				=> $exDetails[$i],
						'productID'			=> $exId[$i], 
						'giftID'			=> 0, 
						'categoryID'		=> $excId[$i], 
						'salePrice'			=> $data['$total'],
						'booking_date' 		=> $exAdateP[$i], 
						'liveDownload'		=> 'no', 
						'persPrice'			=> $exsPrice[$i], 
						'attrPrice'			=> $exPrice[$i], 
						'insPrice'			=> 0, 
						'globalDiscount'	=> 0, 
						'globalCost'		=> 0, 
						'productQty'		=> $exQty[$i], 
						'productWeight'		=> 0, 
						'downloadAmount'	=> 0, 
						'downloadCode'		=> '', 
						'buyCode'			=> md5($saleInsertId), 
						'saleConfirmation'	=> 'yes', 
						'deletedProductName'=> '', 
						'freeShipping'		=> 'no', 
						'wishpur'			=> 0, 
						'platform'			=> 'desktop'
					);
				
				$this->Cart_model->purchaseInsert($purchaseData);
				}

			//////////////////Address Book Insert/////////////////////

				$regAddressData = array(

					'account'			=> $data['$account'], 
					'nm'				=> $data['$b_name'], 
					'em'				=> $data['$b_email'], 
					'addr1'				=> $data['$b_ccode'], 
					'addr2'				=> $data['$b_address'], 
					'addr3'				=> $data['$b_add'], 
					'addr4'				=> $data['$b_town'], 
					'addr5'				=> $data['$b_state'], 
					'addr6'				=> $data['$b_post'], 
					'addr7'				=> '', 
					'addr8'				=> '', 
					'zone'				=> 0
				);

				$this->session->set_userdata('regAddressData', $regAddressData);
				$this->Cart_model->addressBookInsert($regAddressData);

				
			//////////////////Discount Insert/////////////////////

				$discountData = array(

					'cCampaign'		=> '', 
					'cDiscountCode' => $data['$discode'], 
					'cUseDate'		=> $data['$date'], 
					'saleID'		=> $saleInsertId, 
					'discountValue' => $data['$discount']
				);
				if(!empty($data['$discount']) && !empty($data['$discode']))
				{

					$this->Cart_model->discountInsert($discountData);
					
				}

			///////////////////////Update Product Qty/////////////////////

				for($i=0; $i<count($exId); $i++)
				{
					if($exDetails[$i] == 'Package')
					{
						$whereQty = ['id' => $exId[$i]];
						$updateQty = $exStock[$i] - $exQty[$i];
						$prodUpdate = array('pStock' => $updateQty);
						$this->Cart_model->prodUpdate($prodUpdate, $whereQty);
					}
					
				}

/**********************************DPO PAYMENT*****************************/
				if($this->input->post('method')=='DPO')
				{
					$order_total = $this->input->post('total');
	                $billing_first_name = $this->input->post('b_name');
	                $billing_last_name = $this->input->post('b_name');
	                $billing_email = $this->input->post('b_email');
	                $billing_address_1 = $this->input->post('b_address');
	                
	                $billing_postcode = $this->input->post('b_post');

	                $billing_country = $country[0]['cISO_2'];

	                $param = array(
	                    'order_id'            => md5($saleInsertId),
	                    'amount'              =>'<PaymentAmount>'.$order_total.'</PaymentAmount>',
	                    'first_name'          =>'<customerFirstName>'.$billing_first_name.'</customerFirstName>' ,
	                    'last_name'           =>'<customerLastName>'.$billing_last_name.'</customerLastName>',
	                   
	                    'email'               =>'<customerEmail>'.$billing_email.'</customerEmail>',
	                    'address'             =>'<customerAddress>'.$billing_address_1.'</customerAddress>',
	                   
	                    'zipcode'             =>'<customerZip>'.$billing_postcode.'</customerZip>',
	                    'country'             =>'<customerCountry>'.$billing_country.'</customerCountry>',
	                    'ptl_type'            =>($this->ptl_type == 'minutes')? '<PTLtype>minutes</PTLtype>' : "",
	                    'ptl'                 => (!empty($this->ptl))? '<PTL>'.$this->ptl.'</PTL>' : "",
	                    'currency'            => $this->currency
	                );

	                
	                $response =  $this->create_send_xml_request($param);
	                
	                if ($response === FALSE){

	                    echo '1|Payment error: Unable to connect to the payment gateway, please try again';
	                }
	                else
	                {

	                    //convert the XML result into array
	                    $xml = new SimpleXMLElement($response);
	                    if ($xml->Result[0] != '000') {
	                        echo '1|Payment error code: '.$xml->Result[0]. ', '.$xml->ResultExplanation[0];
	                    }

	                        

	                    $paymnetURL = $this->url."/payv2.php?ID=".$xml->TransToken[0];
	                    echo '2|'.$paymnetURL;
	                    //redirect($paymnetURL);
	                }
    /**********************************DPO*****************************/   
	            }
	            else
	            {
	            	$transaction = array(

	                    	 'transaction_id'      => 0,
	                    	 'booking_id'          => 'BOOK_'.date("Ymdhis"),
	                    	 'user_id'		       => $regAddressData['account'],
	                    	 'sale_id'			   => $saleInsertId,
	                    	 'payment_status'      => 0,
	                    	 'Is_package'	       => 0, 
	                    	 'Is_activity'	       => 0, 
	                    	 'transaction_amount'  => $data['$gtotal'], 
	                    	 'discounted_amount'   => $data['$discount'], 
	                    	 'tranaction_type'     => 1, 
	                    	 'payment_type'        => 2, 
	                    	 'trans_date'          => date("Y-m-d h:i:s"), 
							 'reservation_status'  => 0, 
	                    	 'is_lr_used'          => 0, 
	                    	 'is_lr_gained'        => 0.00, 
	                    	 'addon'               => '', 
	                    	 'checkin_checkout'    => 0, 
	                    	 'confirmation_text'   => ''

	                    );

                    $trnasId = $this->packages_model->transInsert($transaction);

                    $where = ['saleID' => $saleInsertId];
					$purdetails = $this->packages_model->orderFetch3($where);
					foreach ($purdetails as $purvalue) 
                    {
                    	if($purvalue['type']=='Package')
                      {
                      	$trnswhere = ['holiday_transaction_id' => $trnasId];
                      	$transactionUpd = array('Is_package'=> 1);
                      	$this->packages_model->trnasUpdate($transactionUpd, $trnswhere);
                      }
                      else
                      {
                      	$trnswhere = ['holiday_transaction_id' => $trnasId];
                      	$transactionUpd = array('Is_activity'=> 1);
                      	$this->packages_model->trnasUpdate($transactionUpd, $trnswhere);
                      }
                  	}

         ///////////////////////////Admin Email Sent///////////////////

				$from= 'no-reply@gamil.com';
				//$to ="testdevloper007@gmail.com";
				$to ="arijit.dutta48@gmail.com";
				
				
				$subject = 'Purchase Package And Activity Details';
				$message = "";
				$message .="<img src='".base_url('assets/images/logo.png')."' alt='' height='42' width='42' align='left'><br><br> 
				<p>Hi Admin,<br>
				<h2>".$data['$b_name']." Payment Status Is Unpaid</h2>
				<h3>".$data['$b_name']." Booking ID is: ".$transaction['booking_id'];
				$purwhere = ['saleID' => $saleInsertId];
            	$Pdetails = $this->packages_model->orderFetch1($purwhere);
            	$count = count($Pdetails);
            	if($count>0)
		        {
				$message .=
				"<br><h3>Package Details:</h3><br> 
					<table style='border: 1px solid black; border-collapse: collapse;'>
						<thead>
	                        <tr>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Package Name</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Package Category</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Package Price</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Package Quantity</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Total</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Package Booking Date</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Package Notes</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Package Buy Code</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Package Date</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Package Time</th>

	                            
	                        </tr>
                    	</thead>
                    	<tbody>";
                	foreach ($Pdetails as $value) 
			        {
			          $total = $value['productQty']*$value['pPrice'];
                $message .=
                			"<tr>
	                    		<td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['pName']."</td>
		                        <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['catname']."</td>
		                        <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>$".$value['pPrice']."</td>
		                        <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['productQty']."</td>
		                        <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$total."</td>
		                        <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['booking_date']."</td>";
		                    
		                        if($data['$note'] != '')
		                        {
		         					$message .="<td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$data['$note']."</td>";
		                    	}
		                    	else
		                    	{
		                    	  $message .="<td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>N/A</td>";
		                    	}
		        $message .= 
								"<td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".md5($saleInsertId)."</td>
		                        <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$data['$date']."</td>
		                        <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$data['$time']."</td>
		                        
                        
                    		</tr>";
                    		}
                $message .= 	
                		"<tbody>
                    </table><br><br>";
                }
                $Adetails = $this->packages_model->orderFetch2($purwhere);
	            $count1 = count($Adetails);
		        if($count1>0)
		        {
                $message .=
                "<h3>Activity Details:</h3><br>

                    <table style='border: 1px solid black; border-collapse: collapse;'>
	                   <thead>
	                   <tr>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Activity Name</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Activity Category</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Activity Price</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Activity Quantity</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Total</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Activity Booking Date</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Activity Notes</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Activity Buy Code</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Activity Date</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Activity Time</th>
	                            
	                        </tr>
	                    </thead>
	                    <tbody>";
	            foreach ($Adetails as $value) 
		        {
                   $total1 = $value['productQty']*$value['activity_price'];
	           $message .=
	                        "<tr>
                          
	                            <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['activity_name']."</td>
	                            <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['theme_name']."</td>
	                            <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>$".$value['activity_price']."</td>
	                            <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['productQty']."</td>
	                            <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$total1."</td>
	                            <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['booking_date']."</td>";
		                    
		                        if($data['$note'] != '')
		                        {
		         					$message .="<td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$data['$note']."</td>";
		                    	}
		                    	else
		                    	{
		                    	  $message .="<td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>N/A</td>";
		                    	}
		       $message .= 
								"<td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".md5($saleInsertId)."</td>
	                            <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['purchaseDate']."</td>
		                        <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['purchaseTime']."</td>
	                            
                            
                        	</tr>";
                        }                      
                $message .=
               			 "</tbody>
                    </table>";
                }
                    if($data['$discount']<0)
                    {
						$message .=
						"<br><h3>Discount Code: ".$data['$discode']."</h3>
						 <br><h3>Discount Price: $".$data['$discount']."</h3>";
					}
				$message .= "<h3>Total Purchase Price: $".$data['$gtotal']."</h3>
				<h3>Buyer Details:</h3><br>
				<table style='border: 1px solid black; border-collapse: collapse;'>
						<thead>
	                        <tr>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Buyer Name:</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Buyer Address:</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Buyer Email</th>
	                        </tr>
                    	</thead>
                    	<tbody>
                    		<tr>
	                    		<td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$data['$b_name']."</td>
		                        <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$data['$b_address'].',&nbsp;'.$data['$b_add'].',&nbsp;'.$data['$b_town'].',&nbsp;'.$data['$b_state'].',&nbsp;'.$data['$b_post']."</td>
		                        <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$data['$b_email']."</td>
                        
                    		</tr>
                    	<tbody>
                    </table><br><br>
				
				Regards,<br><h5>Heritage</h5>";

				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headers .= 'From: '.$from."\r\n".
					'Reply-To: '.$from."\r\n" .
					'X-Mailer: PHP/' . phpversion();

				@mail($to, $subject, $message, $headers);


		///////////////////////////Buyer Email Sent///////////////////

				$from= "arijit.dutta48@gmail.com";
				//$to ="testdevloper007@gmail.com";
				$to = $data['$b_email'];
				
				
				$subject = 'Purchase Package And Activity Details'; 
				$message = "";
				$message .="<img src='".base_url('assets/images/logo.png')."' alt='' height='42' width='42' align='left'><br><br> 
				<p>Hi ".$data['$b_name'].",<br>
				<h2>Your Payment Status Is Unpaid</h2>
				<h3>Your Booking ID is: ".$transaction['booking_id'];
				$purwhere = ['saleID' => $saleInsertId];
            	$Pdetails = $this->packages_model->orderFetch1($purwhere);
            	$count = count($Pdetails);
            	if($count>0)
		        {
				$message .=
				"<br><h3>Package Details:</h3><br> 
					<table style='border: 1px solid black; border-collapse: collapse;'>
						<thead>
	                        <tr>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Package Name</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Package Category</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Package Price</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Package Quantity</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Total</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Package Booking Date</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Package Notes</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Package Buy Code</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Package Date</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Package Time</th>

	                            
	                        </tr>
                    	</thead>
                    	<tbody>";
                	foreach ($Pdetails as $value) 
			        {
			          $total = $value['productQty']*$value['pPrice'];
                $message .=
                			"<tr>
	                    		<td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['pName']."</td>
		                        <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['catname']."</td>
		                        <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>$".$value['pPrice']."</td>
		                        <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['productQty']."</td>
		                        <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$total."</td>
		                        <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['booking_date']."</td>";
		                    
		                        if($data['$note'] != '')
		                        {
		         					$message .="<td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$data['$note']."</td>";
		                    	}
		                    	else
		                    	{
		                    	  $message .="<td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>N/A</td>";
		                    	}
		        $message .= 
								"<td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".md5($saleInsertId)."</td>
		                        <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$data['$date']."</td>
		                        <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$data['$time']."</td>
		                        
                        
                    		</tr>";
                    		}
                $message .= 	
                		"<tbody>
                    </table><br><br>";
                }
                $Adetails = $this->packages_model->orderFetch2($purwhere);
	            $count1 = count($Adetails);
		        if($count1>0)
		        {
                $message .=
                "<h3>Activity Details:</h3><br>

                    <table style='border: 1px solid black; border-collapse: collapse;'>
	                   <thead>
	                   <tr>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Activity Name</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Activity Category</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Activity Price</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Activity Quantity</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Total</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Activity Booking Date</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Activity Notes</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Activity Buy Code</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Activity Date</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Activity Time</th>
	                            
	                        </tr>
	                    </thead>
	                    <tbody>";
	            foreach ($Adetails as $value) 
		        {
                   $total1 = $value['productQty']*$value['activity_price'];
	           $message .=
	                        "<tr>
                          
	                            <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['activity_name']."</td>
	                            <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['theme_name']."</td>
	                            <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>$".$value['activity_price']."</td>
	                            <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['productQty']."</td>
	                            <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$total1."</td>
	                            <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['booking_date']."</td>";
		                    
		                        if($data['$note'] != '')
		                        {
		         					$message .="<td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$data['$note']."</td>";
		                    	}
		                    	else
		                    	{
		                    	  $message .="<td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>N/A</td>";
		                    	}
		       $message .= 
								"<td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".md5($saleInsertId)."</td>
	                            <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['purchaseDate']."</td>
		                        <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['purchaseTime']."</td>
	                            
                            
                        	</tr>";
                        }                      
                $message .=
               			 "</tbody>
                    </table>";
                }
                    if($data['$discount']<0)
                    {
						$message .=
						"<br><h3>Discount Code: ".$data['$discode']."</h3>
						 <br><h3>Discount Price: $".$data['$discount']."</h3>";
					}
				$message .= "<h3>Total Purchase Price: $".$data['$gtotal']."</h3>				
				Regards,<br><h5>Heritage</h5>";

				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headers .= 'From: '.$from."\r\n".
					'Reply-To: '.$from."\r\n" .
					'X-Mailer: PHP/' . phpversion();

				@mail($to, $subject, $message, $headers);

                  	$this->cart->destroy();
				}


				
			}

	}



	public function loginInsert()
	{
		$countrywhere = ['id' => $this->input->post('b_ccode')];
		$country = $this->packages_model->countryFetch($countrywhere);
		if(!empty($this->input->post('discount')))
		{
			$data = array(
					'$id'        => $this->input->post('id'),
					'$b_name'    => $this->input->post('b_name'),
			        '$b_email'   => $this->input->post('b_email'),
			        '$b_ccode'   => $this->input->post('b_ccode'),
			        '$b_address' => $this->input->post('b_address'),
			        '$b_add'     => $this->input->post('b_add'),
			        '$b_town'    => $this->input->post('b_town'),
			        '$b_state'   => $this->input->post('b_state'),
			        '$b_post'    => $this->input->post('b_post'),
			        '$s_name'    => '',
			        '$s_email'   => '',
			        '$s_ccode'   => '',
			        '$s_address' => '',
			        '$s_add'     => '',
			        '$s_town'    => '',
			        '$s_state'   => '',
			        '$s_post'    => '',
			        '$phone'     => '',
			        '$shipcostid'=> '',
			        '$shiprate'  => '',
			        '$account'   => $this->session->userdata('id'),
			        '$note'      => $this->input->post('note'),
			        '$prodId'    => $this->input->post('prodId'),
			        '$catId'     => $this->input->post('catId'),
			        '$quantity'  => $this->input->post('quantity'),
			        '$stock'     => $this->input->post('stock'),
			        '$total'     => $this->input->post('total'),
			        '$subPrice'  => $this->input->post('subPrice'),
			        '$details'   => $this->input->post('details'),
			        '$insur'     => 0,
			        '$gtotal'    => $this->input->post('total'),
			        '$date'      => date("Y-m-d"),
			        '$time'      => date("h:i:s"),
			        '$method'    => $this->input->post('method'),
			        '$price'     => $this->input->post('price'),
			        '$discount'	 => $this->input->post('discount'),
			        '$discode'	 => $this->input->post('discode'),
		        	'$AdateP'	 => $this->input->post('AdateP')
			    );
		}
		else
		{
			$data = array(
				'$id'        => $this->input->post('id'),
				'$b_name'    => $this->input->post('b_name'),
		        '$b_email'   => $this->input->post('b_email'),
		        '$b_ccode'   => $this->input->post('b_ccode'),
		        '$b_address' => $this->input->post('b_address'),
		        '$b_add'     => $this->input->post('b_add'),
		        '$b_town'    => $this->input->post('b_town'),
		        '$b_state'   => $this->input->post('b_state'),
		        '$b_post'    => $this->input->post('b_post'),
		        '$s_name'    => '',
		        '$s_email'   => '',
		        '$s_ccode'   => '',
		        '$s_address' => '',
		        '$s_add'     => '',
		        '$s_town'    => '',
		        '$s_state'   => '',
		        '$s_post'    => '',
		        '$phone'     => '',
		        '$shipcostid'=> '',
		        '$shiprate'  => '',
		        '$account'   => $this->session->userdata('id'),
		        '$note'      => $this->input->post('note'),
		        '$prodId'    => $this->input->post('prodId'),
		        '$catId'     => $this->input->post('catId'),
		        '$quantity'  => $this->input->post('quantity'),
		        '$stock'     => $this->input->post('stock'),
		        '$total'     => $this->input->post('total'),
		        '$subPrice'  => $this->input->post('subPrice'),
		        '$details'   => $this->input->post('details'),
		        '$insur'     => 0,
		        '$gtotal'    => $this->input->post('total'),
		        '$date'      => date("Y-m-d"),
		        '$time'      => date("h:i:s"),
		        '$method'    => $this->input->post('method'),
		        '$price'     => $this->input->post('price'),
		        '$discount'	 => 0,
		        '$discode'	 => '',
		        '$AdateP'	 => $this->input->post('AdateP')
		    );
		}

		$this->session->set_userdata('data', $data);

		$sales     = $this->Cart_model->fetch();

		$invoiceId = $sales[0]['id'] + 1;

		//////////////////Sale Insert/////////////////////		    
				    				
			$saleData = array(
					 'invoiceNo'          => $invoiceId,
					 'account'	          => $data['$id'], 
					 'saleNotes'          => $data['$note'], 
					 'bill_1'	          => $data['$b_name'], 
					 'bill_2'	          => $data['$b_email'], 
					 'bill_3'             => $data['$b_address'], 
					 'bill_4'             => $data['$b_add'], 
					 'bill_5'             => $data['$b_town'], 
					 'bill_6'             => $data['$b_state'], 
					 'bill_7'             => $data['$b_post'], 
					 'bill_8'             => '', 
					 'bill_9'             => $data['$b_ccode'], 
					 'ship_1'             => $data['$s_name'], 
					 'ship_2'             => $data['$s_email'], 
					 'ship_3'             => $data['$s_address'], 
					 'ship_4'             => $data['$s_add'],   
					 'ship_5'             => $data['$s_town'], 
					 'ship_6'             => $data['$s_state'], 
					 'ship_7'             => $data['$s_post'], 
					 'ship_8'             => $data['$phone'], 
					 'buyerAddress'       => '', 
					 'paymentStatus'      => 'shipping', 
					 'gatewayID'          => '', 
					 'taxPaid'            => round(0.00,2), 
					 'taxRate'            => 0, 
					 'couponCode'         => $data['$discode'], 
					 'couponTotal'        => $data['$discount'],  
					 'codeType'           => '', 
					 'subTotal'           => $data['$total'], 
					 'grandTotal'         => $data['$gtotal'], 
					 'shipTotal'          => $data['$shiprate'], 
					 'globalTotal'        => round(0.00,2), 
					 'insuranceTotal'     => $data['$insur'], 
					 'chargeTotal'        => round(0.00,2), 
					 'globalDiscount'     => 0, 
					 'manualDiscount'     => round(0.00,2), 
					 'isPickup'           => 'no', 
					 'shipSetCountry'     => $data['$s_ccode'], 
					 'shipSetArea'        => $data['$shipcostid'], 
					 'setShipRateID'      => 0, 
					 'shipType'           => 'weight', 
					 'cartWeight'         => 0, 
					 'purchaseDate'       => $data['$date'], 
					 'purchaseTime'       => $data['$time'], 
					 'buyCode'            => md5($invoiceId), 
					 'saleConfirmation'   => 'yes', 
					 'paymentMethod'      => $data['$method'], 
					 'ipAddress'          => $this->input->ip_address(), 
					 'restrictCount'      => 0, 
					 'zipLimit'           => 0, 
					 'downloadLock'       => 'no', 
					 'optInNewsletter'    => 'yes', 
					 'paypalErrorTrigger' => 0, 
					 'trackcode'          => '', 
					 'type'               => 'personal', 
					 'wishlist'           => 0, 
					 'platform'           => 'desktop' 
			);
			$saleInsertId = $this->Cart_model->saleInsert($saleData);
			$this->session->set_userdata('saleInsertId', $saleInsertId);
			$buycode = md5($saleInsertId);

		
		//////////////////Status Insert/////////////////////

			$statusData = array(

					'saleID'		=> $saleInsertId, 
					'statusNotes'	=> 'Order Placed on Website', 
					'dateAdded'		=> $data['$date'], 
					'timeAdded'		=> $data['$time'], 
					'orderStatus'	=> 'shipping', 
					'adminUser'		=> 'system', 	
					'visacc'		=> 'no', 
					'account'		=> $data['$id']
			);
			$this->Cart_model->statusInsert($statusData);

		//////////////////Purchase Insert/////////////////////
			$exId      = explode(',',$data['$prodId']);
			$excId     = explode(',',$data['$catId']);
			$exQty     = explode(',',$data['$quantity']);
			$exStock   = explode(',',$data['$stock']);
			$exPrice   = explode(',',$data['$price']);
			$exsPrice  = explode(',',$data['$subPrice']);
			$exDetails = explode(',',$data['$details']);
			$exAdateP  = explode(',',$data['$AdateP']);
			for($i=0; $i<count($exId); $i++)
			{
			$purchaseData = array(

				'purchaseDate'      => $data['$date'], 
				'purchaseTime'      => $data['$time'], 
				'saleID'            => $saleInsertId, 
				'productType'       => 'virtual',
				'type'				=> $exDetails[$i], 
				'productID'			=> $exId[$i], 
				'giftID'			=> 0, 
				'categoryID'		=> $excId[$i], 
				'salePrice'			=> $data['$total'], 
				'booking_date' 		=> $exAdateP[$i],
				'liveDownload'		=> 'no', 
				'persPrice'			=> $exPrice[$i], 
				'attrPrice'			=> $exsPrice[$i], 
				'insPrice'			=> 0, 
				'globalDiscount'	=> 0, 
				'globalCost'		=> 0, 
				'productQty'		=> $exQty[$i], 
				'productWeight'		=> 0, 
				'downloadAmount'	=> 0, 
				'downloadCode'		=> '', 
				'buyCode'			=> md5($saleInsertId), 
				'saleConfirmation'	=> 'yes', 
				'deletedProductName'=> '', 
				'freeShipping'		=> 'no', 
				'wishpur'			=> 0, 
				'platform'			=> 'desktop'
			);
			$this->Cart_model->purchaseInsert($purchaseData);
		}
		//////////////////Address Book Update/////////////////////

			$regAddressData = array(

				'account'			=> $data['$id'], 
				'nm'				=> $data['$b_name'], 
				'em'				=> $data['$b_email'], 
				'addr1'				=> $data['$b_ccode'], 
				'addr2'				=> $data['$b_address'], 
				'addr3'				=> $data['$b_add'], 
				'addr4'				=> $data['$b_town'], 
				'addr5'				=> $data['$b_state'], 
				'addr6'				=> $data['$b_post'], 
				'addr7'				=> '', 
				'addr8'				=> '', 
				'zone'				=> 0
			);

			$this->session->set_userdata('regAddressData', $regAddressData);
			$b_where = ['account' => $data['$id'], 'type'	=> 'bill'];

			$this->Cart_model->addressBookUpdate($regAddressData, $b_where);

			//////////////////Discount Insert/////////////////////

			$discountData = array(

					'cCampaign'		=> '', 
					'cDiscountCode' => $data['$discode'], 
					'cUseDate'		=> $data['$date'], 
					'saleID'		=> $saleInsertId, 
					'discountValue' => $data['$discount']
				);
				if(!empty($data['$discount']) && !empty($data['$discode']))
				{

					$this->Cart_model->discountInsert($discountData);
					
				}


			///////////////////////Update Product Qty/////////////////////
				for($i=0; $i<count($exId); $i++)
				{
					if($exDetails[$i] == 'Package')
					{
						$whereQty = ['id' => $exId[$i]];
						$updateQty = $exStock[$i] - $exQty[$i];
						$prodUpdate = array('pStock' => $updateQty);
						$this->Cart_model->prodUpdate($prodUpdate, $whereQty);
						$prodWhere = ['id' => $exId[$i]];
						$prodDetails = $this->Cart_model->prevQty($prodWhere);
					}
					
				}


/**********************************DPO PAYMENT*****************************/
			if($this->input->post('method')=='DPO')
			{
				$order_total = $this->input->post('total');
                $billing_first_name = $this->input->post('b_name');
                $billing_last_name = $this->input->post('b_name');
               // $billing_phone = $this->clean($user_details['user_contact']);
                $billing_email = $this->input->post('b_email');
                $billing_address_1 = $this->input->post('b_address');
                
                $billing_postcode = $this->input->post('b_post');

                $billing_country = $country[0]['cISO_2'];

                $param = array(
                    'order_id'            => $buycode ,
                    'amount'              =>'<PaymentAmount>'.$order_total.'</PaymentAmount>',
                    'first_name'          =>'<customerFirstName>'.$billing_first_name.'</customerFirstName>' ,
                    'last_name'           =>'<customerLastName>'.$billing_last_name.'</customerLastName>',
                   
                    'email'               =>'<customerEmail>'.$billing_email.'</customerEmail>',
                    'address'             =>'<customerAddress>'.$billing_address_1.'</customerAddress>',
                   
                    'zipcode'             =>'<customerZip>'.$billing_postcode.'</customerZip>',
                    'country'             =>'<customerCountry>'.$billing_country.'</customerCountry>',
                    'ptl_type'            =>($this->ptl_type == 'minutes')? '<PTLtype>minutes</PTLtype>' : "",
                    'ptl'                 => (!empty($this->ptl))? '<PTL>'.$this->ptl.'</PTL>' : "",
                    'currency'            => $this->currency
                );

                //pr($param);

                
                $response =  $this->create_send_xml_request($param);
                
                if ($response === FALSE){

                    echo '1|Payment error: Unable to connect to the payment gateway, please try again';
                }else{

                    //convert the XML result into array
                    $xml = new SimpleXMLElement($response);
                    if ($xml->Result[0] != '000') {
                        echo '1|Payment error code: '.$xml->Result[0]. ', '.$xml->ResultExplanation[0];
                    }

     
                    $paymnetURL = $this->url."/payv2.php?ID=".$xml->TransToken[0];
                    echo '2|'.$paymnetURL;
                    //redirect($paymnetURL);
                }
       /**********************************DPO*****************************/   
            }
            else
            {
            	$transaction = array(

	                    	 'transaction_id'      => 0,
	                    	 'booking_id'          => 'BOOK_'.date("Ymdhis"),
	                    	 'user_id'		       => $regAddressData['account'],
	                    	 'sale_id'			   => $saleInsertId,
	                    	 'payment_status'      => 0,
	                    	 'Is_package'	       => 0, 
	                    	 'Is_activity'	       => 0, 
	                    	 'transaction_amount'  => $data['$gtotal'], 
	                    	 'discounted_amount'   => $data['$discount'], 
	                    	 'tranaction_type'     => 1, 
	                    	 'payment_type'        => 2, 
	                    	 'trans_date'          => date("Y-m-d h:i:s"), 
							 'reservation_status'  => 0, 
	                    	 'is_lr_used'          => 0, 
	                    	 'is_lr_gained'        => 0.00, 
	                    	 'addon'               => '', 
	                    	 'checkin_checkout'    => 0, 
	                    	 'confirmation_text'   => ''

	                    );
                    $trnasId = $this->packages_model->transInsert($transaction);

                    $where = ['saleID' => $saleInsertId];
					$purdetails = $this->packages_model->orderFetch3($where);
					foreach ($purdetails as $purvalue) 
                    {
                    	if($purvalue['type']=='Package')
                      {
                      	$trnswhere = ['holiday_transaction_id' => $trnasId];
                      	$transactionUpd = array('Is_package'=> 1);
                      	$this->packages_model->trnasUpdate($transactionUpd, $trnswhere);
                      }
                      else
                      {
                      	$trnswhere = ['holiday_transaction_id' => $trnasId];
                      	$transactionUpd = array('Is_activity'=> 1);
                      	$this->packages_model->trnasUpdate($transactionUpd, $trnswhere);
                      }
                  	}

        ///////////////////////////Admin Email Sent///////////////////

				$from= 'no-reply@gamil.com';
				//$to ="testdevloper007@gmail.com";
				$to ="arijit.dutta48@gmail.com";
				
				
				$subject = 'Purchase Package And Activity Details';
				$message = "";
				$message .="<img src='".base_url('assets/images/logo.png')."' alt='' height='42' width='42' align='left'><br><br> 
				<p>Hi Admin,<br>
				<h2>".$data['$b_name']." Payment Status Is Unpaid</h2>
				<h3>".$data['$b_name']." Booking ID is: ".$transaction['booking_id'];
				$purwhere = ['saleID' => $saleInsertId];
            	$Pdetails = $this->packages_model->orderFetch1($purwhere);
            	$count = count($Pdetails);
            	if($count>0)
		        {
				$message .=
				"<br><h3>Package Details:</h3><br> 
					<table style='border: 1px solid black; border-collapse: collapse;'>
						<thead>
	                        <tr>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Package Name</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Package Category</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Package Price</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Package Quantity</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Total</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Package Booking Date</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Package Notes</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Package Buy Code</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Package Date</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Package Time</th>

	                            
	                        </tr>
                    	</thead>
                    	<tbody>";
                	foreach ($Pdetails as $value) 
			        {
			          $total = $value['productQty']*$value['pPrice'];
                $message .=
                			"<tr>
	                    		<td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['pName']."</td>
		                        <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['catname']."</td>
		                        <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['pPrice']."</td>
		                        <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['productQty']."</td>
		                        <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$total."</td>
		                        <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['booking_date']."</td>";
		                    
		                        if($data['$note'] != '')
		                        {
		         					$message .="<td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$data['$note']."</td>";
		                    	}
		                    	else
		                    	{
		                    	  $message .="<td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>N/A</td>";
		                    	}
		        $message .= 
								"<td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".md5($saleInsertId)."</td>
		                        <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$data['$date']."</td>
		                        <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$data['$time']."</td>
		                        
                        
                    		</tr>";
                    		}
                $message .= 	
                		"<tbody>
                    </table><br><br>";
                }
                $Adetails = $this->packages_model->orderFetch2($purwhere);
	            $count1 = count($Adetails);
		        if($count1>0)
		        {
                $message .=
                "<h3>Activity Details:</h3><br>

                    <table style='border: 1px solid black; border-collapse: collapse;'>
	                   <thead>
	                   <tr>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Activity Name</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Activity Category</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Activity Price</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Activity Quantity</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Total</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Activity Booking Date</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Activity Notes</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Activity Buy Code</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Activity Date</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Activity Time</th>
	                            
	                        </tr>
	                    </thead>
	                    <tbody>";
	            foreach ($Adetails as $value) 
		        {
                   $total1 = $value['productQty']*$value['activity_price'];
	           $message .=
	                        "<tr>
                          
	                            <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['activity_name']."</td>
	                            <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['theme_name']."</td>
	                            <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['activity_price']."</td>
	                            <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['productQty']."</td>
	                            <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$total1."</td>
	                            <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['booking_date']."</td>";
		                    
		                        if($data['$note'] != '')
		                        {
		         					$message .="<td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$data['$note']."</td>";
		                    	}
		                    	else
		                    	{
		                    	  $message .="<td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>N/A</td>";
		                    	}
		       $message .= 
								"<td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".md5($saleInsertId)."</td>
	                            <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['purchaseDate']."</td>
		                        <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['purchaseTime']."</td>
	                            
                            
                        	</tr>";
                        }                      
                $message .=
               			 "</tbody>
                    </table>";
                }
                    if($data['$discount']<0)
                    {
						$message .=
						"<br><h3>Discount Code: ".$data['$discode']."</h3>
						 <br><h3>Discount Price: ".$data['$discount']."</h3>";
					}
				$message .= "<h3>Total Purchase Price: ".$data['$gtotal']."</h3>
				<h3>Buyer Details:</h3><br>
				<table style='border: 1px solid black; border-collapse: collapse;'>
						<thead>
	                        <tr>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Buyer Name:</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Buyer Address:</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Buyer Email</th>
	                        </tr>
                    	</thead>
                    	<tbody>
                    		<tr>
	                    		<td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$data['$b_name']."</td>
		                        <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$data['$b_address'].',&nbsp;'.$data['$b_add'].',&nbsp;'.$data['$b_town'].',&nbsp;'.$data['$b_state'].',&nbsp;'.$data['$b_post']."</td>
		                        <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$data['$b_email']."</td>
                        
                    		</tr>
                    	<tbody>
                    </table><br><br>
				
				Regards,<br><h5>Heritage</h5>";

				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headers .= 'From: '.$from."\r\n".
					'Reply-To: '.$from."\r\n" .
					'X-Mailer: PHP/' . phpversion();

				@mail($to, $subject, $message, $headers);


		///////////////////////////Buyer Email Sent///////////////////

				$from= "arijit.dutta48@gmail.com";
				//$to ="testdevloper007@gmail.com";
				$to = $data['$b_email'];
				
				
				$subject = 'Purchase Package And Activity Details'; 
				$message = "";
				$message .="<img src='".base_url('assets/images/logo.png')."' alt='' height='42' width='42' align='left'><br><br> 
				<p>Hi ".$data['$b_name'].",<br>
				<h2>Your Payment Status Is Unpaid</h2>
				<h3>Your Booking ID is: ".$transaction['booking_id'];
				$purwhere = ['saleID' => $saleInsertId];
            	$Pdetails = $this->packages_model->orderFetch1($purwhere);
            	$count = count($Pdetails);
            	if($count>0)
		        {
				$message .=
				"<br><h3>Package Details:</h3><br> 
					<table style='border: 1px solid black; border-collapse: collapse;'>
						<thead>
	                        <tr>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Package Name</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Package Category</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Package Price</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Package Quantity</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Total</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Package Booking Date</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Package Notes</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Package Buy Code</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Package Date</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Package Time</th>

	                            
	                        </tr>
                    	</thead>
                    	<tbody>";
                	foreach ($Pdetails as $value) 
			        {
			          $total = $value['productQty']*$value['pPrice'];
                $message .=
                			"<tr>
	                    		<td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['pName']."</td>
		                        <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['catname']."</td>
		                        <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['pPrice']."</td>
		                        <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['productQty']."</td>
		                        <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$total."</td>
		                        <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['booking_date']."</td>";
		                    
		                        if($data['$note'] != '')
		                        {
		         					$message .="<td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$data['$note']."</td>";
		                    	}
		                    	else
		                    	{
		                    	  $message .="<td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>N/A</td>";
		                    	}
		        $message .= 
								"<td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".md5($saleInsertId)."</td>
		                        <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$data['$date']."</td>
		                        <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$data['$time']."</td>
		                        
                        
                    		</tr>";
                    		}
                $message .= 	
                		"<tbody>
                    </table><br><br>";
                }
                $Adetails = $this->packages_model->orderFetch2($purwhere);
	            $count1 = count($Adetails);
		        if($count1>0)
		        {
                $message .=
                "<h3>Activity Details:</h3><br>

                    <table style='border: 1px solid black; border-collapse: collapse;'>
	                   <thead>
	                   <tr>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Activity Name</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Activity Category</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Activity Price</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Activity Quantity</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Total</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Activity Booking Date</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Activity Notes</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Activity Buy Code</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Activity Date</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Activity Time</th>
	                            
	                        </tr>
	                    </thead>
	                    <tbody>";
	            foreach ($Adetails as $value) 
		        {
                   $total1 = $value['productQty']*$value['activity_price'];
	           $message .=
	                        "<tr>
                          
	                            <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['activity_name']."</td>
	                            <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['theme_name']."</td>
	                            <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['activity_price']."</td>
	                            <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['productQty']."</td>
	                            <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$total1."</td>
	                            <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['booking_date']."</td>";
		                    
		                        if($data['$note'] != '')
		                        {
		         					$message .="<td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$data['$note']."</td>";
		                    	}
		                    	else
		                    	{
		                    	  $message .="<td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>N/A</td>";
		                    	}
		       $message .= 
								"<td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".md5($saleInsertId)."</td>
	                            <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['purchaseDate']."</td>
		                        <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['purchaseTime']."</td>
	                            
                            
                        	</tr>";
                        }                      
                $message .=
               			 "</tbody>
                    </table>";
                }
                    if($data['$discount']<0)
                    {
						$message .=
						"<br><h3>Discount Code: ".$data['$discode']."</h3>
						 <br><h3>Discount Price: ".$data['$discount']."</h3>";
					}
				$message .= "<h3>Total Purchase Price: ".$data['$gtotal']."</h3>				
				Regards,<br><h5>Heritage</h5>";

				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headers .= 'From: '.$from."\r\n".
					'Reply-To: '.$from."\r\n" .
					'X-Mailer: PHP/' . phpversion();

				@mail($to, $subject, $message, $headers);

                  	$this->cart->destroy();
			}
			
	}




	public function dporeturn()
    {
    	$regAddressData = $this->session->userdata('regAddressData');
    	$data = $this->session->userdata('data');
    	$saleInsertId = $this->session->userdata('saleInsertId');
    	$transactionToken = $_GET['TransactionToken'];
		$transToken = $this->session->set_userdata('$transactionToken', $transactionToken);
        if (!isset($transactionToken)) {
        	$this->session->set_flashdata('err_msg', 'Transaction Token error, please contact support center');
            redirect(base_url('cart/loginAccess'));
        }
            
        //get verify token response from 3g
        $response = $this->verifytoken($transactionToken);
        if ($response) {
            if ($response->Result[0] == '000') 
            {
        			$transaction = array(

	                    	 'transaction_id'      => $transactionToken,
	                    	 'booking_id'          => 'BOOK_'.date("Ymdhis"),
	                    	 'user_id'		       => $data['$account'],
	                    	 'sale_id'			   => $saleInsertId,
	                    	 'payment_status'      => 1,
	                    	 'Is_package'	       => 0, 
	                    	 'Is_activity'	       => 0, 
	                    	 'transaction_amount'  => $data['$gtotal'], 
	                    	 'discounted_amount'   => $data['$discount'], 
	                    	 'tranaction_type'     => 1, 
	                    	 'payment_type'        => 1, 
	                    	 'trans_date'          => date("Y-m-d h:i:s"), 
							 'reservation_status'  => 0, 
	                    	 'is_lr_used'          => 0, 
	                    	 'is_lr_gained'        => 0.00, 
	                    	 'addon'               => '', 
	                    	 'checkin_checkout'    => 0, 
	                    	 'confirmation_text'   => ''

	                    );
                    $trnasId = $this->packages_model->transInsert($transaction);
                    $where = ['saleID' => $saleInsertId];
					$purdetails = $this->packages_model->orderFetch3($where);
					foreach ($purdetails as $purvalue) 
                    {
                    	if($purvalue['type']=='Package')
                      {
                      	$trnswhere = ['holiday_transaction_id' => $trnasId];
                      	$transactionUpd = array('Is_package'=> 1);
                      	$this->packages_model->trnasUpdate($transactionUpd, $trnswhere);
                      }
                      else
                      {
                      	$trnswhere = ['holiday_transaction_id' => $trnasId];
                      	$transactionUpd = array('Is_activity'=> 1);
                      	$this->packages_model->trnasUpdate($transactionUpd, $trnswhere);
                      }
                  	}

        ///////////////////////////Admin Email Sent///////////////////

				$from= 'no-reply@gamil.com';
				//$to ="testdevloper007@gmail.com";
				$to ="arijit.dutta48@gmail.com";
				
				
				$subject = 'Purchase Package And Activity Details';
				$message = "";
				$message .="<img src='".base_url('assets/images/logo.png')."' alt='' height='42' width='42' align='left'><br><br> 
				<p>Hi Admin,<br>
				<h2>".$data['$b_name']." Your Payment Proceed Successfully And You Paid By Card</h2>
				<h3>".$data['$b_name']." Booking ID is: ".$transaction['booking_id']."<h3>".$data['$b_name']." Transaction ID is: ".$transactionToken;
				$purwhere = ['saleID' => $saleInsertId];
            	$Pdetails = $this->packages_model->orderFetch1($purwhere);
            	$count = count($Pdetails);
            	if($count>0)
		        {
				$message .=
				"</h3><br><h3>Package Details:</h3><br> 
					<table style='border: 1px solid black; border-collapse: collapse;'>
						<thead>
	                        <tr>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Package Name</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Package Category</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Package Price</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Package Quantity</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Total</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Package Booking Date</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Package Notes</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Package Buy Code</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Package Date</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Package Time</th>

	                            
	                        </tr>
                    	</thead>
                    	<tbody>";
                	foreach ($Pdetails as $value) 
			        {
			          $total = $value['productQty']*$value['pPrice'];
                $message .=
                			"<tr>
	                    		<td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['pName']."</td>
		                        <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['catname']."</td>
		                        <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['pPrice']."</td>
		                        <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['productQty']."</td>
		                        <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$total."</td>
		                        <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['booking_date']."</td>";
		                    
		                        if($data['$note'] != '')
		                        {
		         					$message .="<td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$data['$note']."</td>";
		                    	}
		                    	else
		                    	{
		                    	  $message .="<td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>N/A</td>";
		                    	}
		        $message .= 
								"<td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".md5($saleInsertId)."</td>
		                        <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$data['$date']."</td>
		                        <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$data['$time']."</td>
		                        
                        
                    		</tr>";
                    		}
                $message .= 	
                		"<tbody>
                    </table><br><br>";
                }
                $Adetails = $this->packages_model->orderFetch2($purwhere);
	            $count1 = count($Adetails);
		        if($count1>0)
		        {
                $message .=
                "<h3>Activity Details:</h3><br>

                    <table style='border: 1px solid black; border-collapse: collapse;'>
	                   <thead>
	                   <tr>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Activity Name</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Activity Category</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Activity Price</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Activity Quantity</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Total</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Activity Booking Date</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Activity Notes</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Activity Buy Code</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Activity Date</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Activity Time</th>
	                            
	                        </tr>
	                    </thead>
	                    <tbody>";
	            foreach ($Adetails as $value) 
		        {
                   $total1 = $value['productQty']*$value['activity_price'];
	           $message .=
	                        "<tr>
                          
	                            <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['activity_name']."</td>
	                            <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['theme_name']."</td>
	                            <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['activity_price']."</td>
	                            <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['productQty']."</td>
	                            <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$total1."</td>
	                            <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['booking_date']."</td>";
		                    
		                        if($data['$note'] != '')
		                        {
		         					$message .="<td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$data['$note']."</td>";
		                    	}
		                    	else
		                    	{
		                    	  $message .="<td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>N/A</td>";
		                    	}
		       $message .= 
								"<td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".md5($saleInsertId)."</td>
	                            <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['purchaseDate']."</td>
		                        <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['purchaseTime']."</td>
	                            
                            
                        	</tr>";
                        }                      
                $message .=
               			 "</tbody>
                    </table>";
                }
                    if($data['$discount']<0)
                    {
						$message .=
						"<br><h3>Discount Code: ".$data['$discode']."</h3>
						 <br><h3>Discount Price: ".$data['$discount']."</h3>";
					}
				$message .= "<h3>Total Purchase Price: ".$data['$gtotal']."</h3>
				<h3>Buyer Details:</h3><br>
				<table style='border: 1px solid black; border-collapse: collapse;'>
						<thead>
	                        <tr>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Buyer Name:</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Buyer Address:</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Buyer Email</th>
	                        </tr>
                    	</thead>
                    	<tbody>
                    		<tr>
	                    		<td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$data['$b_name']."</td>
		                        <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$data['$b_address'].',&nbsp;'.$data['$b_add'].',&nbsp;'.$data['$b_town'].',&nbsp;'.$data['$b_state'].',&nbsp;'.$data['$b_post']."</td>
		                        <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$data['$b_email']."</td>
                        
                    		</tr>
                    	<tbody>
                    </table><br><br>
				
				Regards,<br><h5>Heritage</h5>";

				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headers .= 'From: '.$from."\r\n".
					'Reply-To: '.$from."\r\n" .
					'X-Mailer: PHP/' . phpversion();

				@mail($to, $subject, $message, $headers);


		///////////////////////////Buyer Email Sent///////////////////

				$from= "arijit.dutta48@gmail.com";
				//$to ="testdevloper007@gmail.com";
				$to = $data['$b_email'];
				
				
				$subject = 'Purchase Package And Activity Details'; 
				$message = "";
				$message .="<img src='".base_url('assets/images/logo.png')."' alt='' height='42' width='42' align='left'><br><br> 
				<p>Hi ".$data['$b_name'].",<br>
				<h2>Your Payment Proceed Successfully And You Paid By Card</h2>
				<h3>Your Booking ID is: ".$transaction['booking_id']."</h3><h3>Your Transaction ID is: ".$transactionToken;
				$purwhere = ['saleID' => $saleInsertId];
            	$Pdetails = $this->packages_model->orderFetch1($purwhere);
            	$count = count($Pdetails);
            	if($count>0)
		        {
				$message .=
				"</h3><br><h3>Package Details:</h3><br> 
					<table style='border: 1px solid black; border-collapse: collapse;'>
						<thead>
	                        <tr>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Package Name</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Package Category</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Package Price</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Package Quantity</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Total</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Package Booking Date</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Package Notes</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Package Buy Code</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Package Date</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Package Time</th>

	                            
	                        </tr>
                    	</thead>
                    	<tbody>";
                	foreach ($Pdetails as $value) 
			        {
			          $total = $value['productQty']*$value['pPrice'];
                $message .=
                			"<tr>
	                    		<td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['pName']."</td>
		                        <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['catname']."</td>
		                        <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['pPrice']."</td>
		                        <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['productQty']."</td>
		                        <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$total."</td>
		                        <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['booking_date']."</td>";
		                    
		                        if($data['$note'] != '')
		                        {
		         					$message .="<td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$data['$note']."</td>";
		                    	}
		                    	else
		                    	{
		                    	  $message .="<td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>N/A</td>";
		                    	}
		        $message .= 
								"<td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".md5($saleInsertId)."</td>
		                        <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$data['$date']."</td>
		                        <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$data['$time']."</td>
		                        
                        
                    		</tr>";
                    		}
                $message .= 	
                		"<tbody>
                    </table><br><br>";
                }
                $Adetails = $this->packages_model->orderFetch2($purwhere);
	            $count1 = count($Adetails);
		        if($count1>0)
		        {
                $message .=
                "<h3>Activity Details:</h3><br>

                    <table style='border: 1px solid black; border-collapse: collapse;'>
	                   <thead>
	                   <tr>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Activity Name</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Activity Category</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Activity Price</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Activity Quantity</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Total</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Activity Booking Date</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Activity Notes</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Activity Buy Code</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Activity Date</th>
	                            <th style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>Activity Time</th>
	                            
	                        </tr>
	                    </thead>
	                    <tbody>";
	            foreach ($Adetails as $value) 
		        {
                   $total1 = $value['productQty']*$value['activity_price'];
	           $message .=
	                        "<tr>
                          
	                            <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['activity_name']."</td>
	                            <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['theme_name']."</td>
	                            <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['activity_price']."</td>
	                            <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['productQty']."</td>
	                            <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$total1."</td>
	                            <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['booking_date']."</td>";
		                    
		                        if($data['$note'] != '')
		                        {
		         					$message .="<td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$data['$note']."</td>";
		                    	}
		                    	else
		                    	{
		                    	  $message .="<td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>N/A</td>";
		                    	}
		       $message .= 
								"<td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".md5($saleInsertId)."</td>
	                            <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['purchaseDate']."</td>
		                        <td align='center' style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>".$value['purchaseTime']."</td>
	                            
                            
                        	</tr>";
                        }                      
                $message .=
               			 "</tbody>
                    </table>";
                }
                    if($data['$discount']<0)
                    {
						$message .=
						"<br><h3>Discount Code: ".$data['$discode']."</h3>
						 <br><h3>Discount Price: ".$data['$discount']."</h3>";
					}
				$message .= "<h3>Total Purchase Price: ".$data['$gtotal']."</h3>				
				Regards,<br><h5>Heritage</h5>";

				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headers .= 'From: '.$from."\r\n".
					'Reply-To: '.$from."\r\n" .
					'X-Mailer: PHP/' . phpversion();

				@mail($to, $subject, $message, $headers);

                  	
			
                // $this->utility->setMsg('The transaction paid successfully', 'success');
			    if($data['$account'] == 1)
			    {
                	$this->session->set_flashdata('success_msg', 'Registration And Order Placed successfully, Please Check Yor Email');
            	}
            	elseif($data['$account'] == 0)
			    {
                	$this->session->set_flashdata('success_msg', 'Order Placed successfully, Please Check Yor Email');
            	}
            	else
			    {
                	$this->session->set_flashdata('success_msg', 'Order Placed successfully, Please Check Yor Email');
            	}
            		$this->cart->destroy();
                  	$this->session->unset_userdata('regAddressData');
			    	$this->session->unset_userdata('data');
			    	$this->session->unset_userdata('saleInsertId');
			    	$this->session->unset_userdata('trnasId');
			    	$this->session->unset_userdata('transaction');
                redirect(base_url() . 'cart/thankyou');
            }
            else
            {
                $error_code = $response->Result[0];
                $error_desc = $response->ResultExplanation[0];
                // $this->utility->setMsg('Payment Failed: '.$error_code. ', '.$error_desc, 'error');
                $this->session->set_flashdata('err_msg', 'Payment Failed: '.$error_code. ', '.$error_desc);
				redirect(base_url('cart/loginAccess'));
            }
        }
        else{
            // $this->utility->setMsg('Varification error: Unable to connect to the payment gateway, please try again', 'error');
            $this->session->set_flashdata('err_msg', 'Varification error: Unable to connect to the payment gateway, please try again');
			redirect(base_url('cart/loginAccess'));
        }  
    }



    public function dpoCancel()
    {
        $cancel = $_POST;
        $this->session->set_userdata('cancel', $cancel);
        redirect(base_url('cart/cancel'));
    }

	public function create_send_xml_request($param){

        //URL for 3G to send the buyer to after review and continue from 3G.
        $service = '';
        $serviceType = 3854;
        $serviceDesc = "Test Product";
        $service .= '<Service>
                            <ServiceType>'.$serviceType.'</ServiceType>
                            <ServiceDescription>'.$serviceDesc.'</ServiceDescription>
                            <ServiceDate>'.date('Y-m-d H:i').'</ServiceDate>
                        </Service>';
        
        $input_xml = '<?xml version="1.0" encoding="utf-8"?>
	                    <API3G>
	                        <CompanyToken>'.$this->company_token.'</CompanyToken>
	                        <Request>createToken</Request>
	                        <Transaction>'.$param["first_name"].
										$param["last_name"].
										$param["email"].
										$param["address"].
										$param["zipcode"].
										$param["country"].
										$param["amount"].'
	                            <PaymentCurrency>'.$param["currency"].'</PaymentCurrency>
	                            <CompanyRef>'.$param["order_id"].'</CompanyRef>
	                            <RedirectURL>'.htmlspecialchars ($this->returnURL).'</RedirectURL>
	                            <BackURL>'.htmlspecialchars ($this->cancelURL).'</BackURL>
	                            <CompanyRefUnique>0</CompanyRefUnique>
	                            '.$param["ptl_type"].
	                              $param["ptl"].'
	                        </Transaction>
	                        <Services>'.$service.'</Services>
	                    </API3G>';
        $response = $this->createCURL($input_xml);
        return $response;
    }



    public function verifytoken($transactionToken){
        $input_xml = '<?xml version="1.0" encoding="utf-8"?>
                    <API3G>
                      <CompanyToken>'.$this->company_token.'</CompanyToken>
                      <Request>verifyToken</Request>
                      <TransactionToken>'.$transactionToken.'</TransactionToken>
                    </API3G>';

        $response = $this->createCURL($input_xml);
        if ($response !==  FALSE) {
            //convert the XML result into array
            $xml = new SimpleXMLElement($response);
            return $xml;
        }
        return false;
    }  

    //generate Curl and return response
    public function createCURL($input_xml){

        $url =$this->url."/API/v6/";
        //echo $input_xml;exit;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSLVERSION,6);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $input_xml);

        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    public function clean($string) {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
    }



    public function thankyou()
    {
    	if($this->session->userdata('$transactionToken') == true)
    	{
	    	$content_data = array();
			$about = $this->cms_model->about_fetchRecord();
			$content_data['thank'] = $about;
			$contact = $this->settings_model->contact_fetchRecord();
			$content_data['contact'] = $contact;
			$cat = $this->packages_model->getcatlist();
			$catId = $cat[0]['id'];
			$wher = ['category'=> $catId];
			$deal = $this->packages_model->sidebarFetch($wher);
	    	$content_data['deal'] = $deal;
			$this->load->view('thankyou', $content_data);
		}
		else
		{
			redirect(base_url());
		}
		$this->session->unset_userdata('$transactionToken');
    }



    public function cancel()
    {
    	if($this->session->userdata('cancel') == true)
    	{
	    	$content_data = array();
			$about = $this->cms_model->about_fetchRecord();
			$content_data['cancel'] = $about;
			$contact = $this->settings_model->contact_fetchRecord();
			$content_data['contact'] = $contact;
			$cat = $this->packages_model->getcatlist();
			$catId = $cat[0]['id'];
			$wher = ['category'=> $catId];
			$deal = $this->packages_model->sidebarFetch($wher);
	    	$content_data['deal'] = $deal;
			$this->load->view('cancel', $content_data);
		}
		else
		{
			redirect(base_url());
		}
		$this->session->unset_userdata('cancel');
    }
	
}
