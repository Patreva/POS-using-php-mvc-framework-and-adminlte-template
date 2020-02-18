<?php

use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;


class ControllerSales{

	/*=============================================
	SHOW SALES
	=============================================*/

	static public function ctrShowSales($item, $value){

		$table = "sales";

		$answer = ModelSales::mdlShowSales($table, $item, $value);

		return $answer;

	}

	/*=============================================
	CREATE SALE
	=============================================*/

	static public function ctrCreateSale(){

		if(isset($_POST["newSale"])){

			/*=============================================
			 REDUCE THE STOCK AND INCREASE SALES OF THE PRODUCT
			=============================================*/

			$productsList = json_decode($_POST["productsList"], true);

			foreach ($productsList as $key => $value) {
				
			   $tableProducts = "products";

			    $item = "id";
			    $valueProductId = $value["id"];
			    $order = "id";

			    $getProduct = ProductsModel::mdlShowProducts($tableProducts, $item, $valueProductId, $order);

				$item1a = "sales";
				$value1a = $value["quantity"] + $getProduct["sales"];

			    $newSales = ProductsModel::mdlUpdateProduct($tableProducts, $item1a, $value1a, $valueProductId);

				$item1b = "stock";
				$value1b = $value["stock"];

				$newStock = ProductsModel::mdlUpdateProduct($tableProducts, $item1b, $value1b, $valueProductId);

			}

			/*=============================================
			SAVE THE SALE
			=============================================*/	

			$table = "sales";

			$data = array("idSeller"=>$_POST["idSeller"],
						   "code"=>$_POST["newSale"],
						   "products"=>$_POST["productsList"],
						   "tax"=>$_POST["newTaxPrice"],
						   "netPrice"=>$_POST["newNetPrice"],
						   "totalPrice"=>$_POST["saleTotal"],
						   "paymentMethod"=>$_POST["listPaymentMethod"]);

			$answer = ModelSales::mdlAddSale($table, $data);

			if($answer == "ok"){

				// $printer = "epson20";

				// $connector = new WindowsPrintConnector($printer);

				// $printer = new Printer($connector);

				// $printer -> setJustification(Printer::JUSTIFY_CENTER);

				// $printer -> text(date("Y-m-d H:i:s")."\n");//Invoice date

				// $printer -> feed(1); //We feed paper 1 time*/

				// $printer -> text("Inventory System"."\n");//Company name

				// $printer -> text("Invoice N.".$_POST["newSale"]."\n");//Invoice number

				// $printer -> feed(1); //We feed paper 1 time*/

				// $tableSeller = "users";
				// $item = "id";
				// $seller = $_POST["idSeller"];

				// $getSeller = UsersModel::MdlShowUsers($tableSeller, $item, $seller);

				// $printer -> text("Seller: ".$getSeller["name"]."\n");//Seller's name

				// $printer -> feed(1); //We feed paper 1 time*/

				// foreach ($productsList as $key => $value) {

				// 	$printer->setJustification(Printer::JUSTIFY_LEFT);

				// 	$printer->text($value["description"]."\n");//Product's name

				// 	$printer->setJustification(Printer::JUSTIFY_RIGHT);

				// 	$printer->text("Ksh ".number_format($value["price"],2)." Units x ".$value["quantity"]." = Ksh ".number_format($value["totalPrice"],2)."\n");

				// }

				// $printer -> feed(1); //We feed paper 1 time*/			
				
				// $printer->text("NET: Ksh ".number_format($_POST["newNetPrice"],2)."\n"); //net price

				// $printer->text("TAX: Ksh ".number_format($_POST["newTaxPrice"],2)."\n"); //tax value

				// $printer->text("--------\n");

				// $printer->text("TOTAL: Ksh ".number_format($_POST["saleTotal"],2)."\n"); //ahora va el total

				// $printer -> feed(1); //We feed paper 1 time*/	

				// $printer->text("Thanks for your purchase"); //We can add a footer

				// $printer -> feed(3); //We feed paper 3 times*/

				// $printer -> cut(); //We cut the paper, if the printer has the option

				// $printer -> pulse(); //Through the printer we send a pulse to open the cash drawer.

				// $printer -> close(); 

				echo'<script>

				localStorage.removeItem("range");

				swal({
					  type: "success",
					  title: "The sale has been succesfully added",
					  showConfirmButton: true,
					  confirmButtonText: "Okay"
					  }).then((result) => {
								if (result.value) {

								window.location = "sales";

								}
							})

				</script>';

			}

		}

	}

	/*=============================================
	EDIT SALE
	=============================================*/

	static public function ctrEditSale(){

		if(isset($_POST["editSale"])){

			/*=============================================
			FORMAT PRODUCTS TABLES
			=============================================*/
			$table = "sales";

			$item = "code";
			$value = $_POST["editSale"];

			$getSale = ModelSales::mdlShowSales($table, $item, $value);

			/*=============================================
			CHECK IF THERE'S ANY EDITED SALE
			=============================================*/

			if($_POST["productsList"] == ""){

				$productsList = $getSale["products"];
				$productChange = false;


			}else{

				$productsList = $_POST["productsList"];
				$productChange = true;
			}

			if($productChange){

				$products =  json_decode($getSale["products"], true);

				foreach ($products as $key => $value) {
					
					$tableProducts = "products";

					$item = "id";
					$value1 = $value["id"];
					$order = "id";

					$getProduct = ProductsModel::mdlShowProducts($tableProducts, $item, $value, $order);

					$item1a = "sales";
					$value1a = $getProduct["sales"] - $value["quantity"];

					$newSales = ProductsModel::mdlUpdateProduct($tableProducts, $item1a, $value1a, $value);

					$item1b = "stock";
					$value1b = $value["quantity"] + $getProduct["stock"];

					$stockNew = ProductsModel::mdlUpdateProduct($tableProducts, $item1b, $value1b, $value);

				}


				/*=============================================
				 REDUCE THE STOCK AND INCREMENT PRODUCT SALES
				=============================================*/

				$productsList_2 = json_decode($productsList, true);


				foreach ($productsList_2 as $key => $value) {
					
					$tableProducts_2 = "products";

					$item_2 = "id";
					$value_2 = $value["id"];
					$order = "id";

					$getProduct_2 = ProductsModel::mdlShowProducts($tableProducts_2, $item_2, $value_2, $order);

					$item1a_2 = "sales";
					$value1a_2 = $value["quantity"] + $getProduct_2["sales"];

					$newSales_2 = ProductsModel::mdlUpdateProduct($tableProducts_2, $item1a_2, $value1a_2, $value_2);

					$item1b_2 = "stock";
					$value1b_2 = $getProduct_2["stock"] - $value["quantity"];

					$newStock_2 = ProductsModel::mdlUpdateProduct($tableProducts_2, $item1b_2, $value1b_2, $value_2);

				}

			}

			/*=============================================
			SAVE PURCHASE CHANGES
			=============================================*/	

			$data = array("idSeller"=>$_POST["idSeller"],
						   "code"=>$_POST["editSale"],
						   "products"=>$productsList,
						   "tax"=>$_POST["newTaxPrice"],
						   "netPrice"=>$_POST["newNetPrice"],
						   "totalPrice"=>$_POST["saleTotal"],
						   "paymentMethod"=>$_POST["listPaymentMethod"]);


			$answer = ModelSales::mdleditSale($table, $data);

			if($answer == "ok"){

				echo'<script>

				localStorage.removeItem("range");

				swal({
					  type: "success",
					  title: "The sale has been edited correctly",
					  showConfirmButton: true,
					  confirmButtonText: "Close"
					  }).then((result) => {
								if (result.value) {

								window.location = "sales";

								}
							})

				</script>';

			}

		}

	}

	/*=============================================
	Delete Sale
	=============================================*/

	static public function ctrDeleteSale(){

		if(isset($_GET["idSale"])){

			$table = "sales";

			$item = "id";
			$value = $_GET["idSale"];

			$getSale = ModelSales::mdlShowSales($table, $item, $value);

			/*=============================================
			FORMAT PRODUCTS TABLE
			=============================================*/

			$products =  json_decode($getSale["products"], true);

			foreach ($products as $key => $value) {
				
				$tableProducts = "products";

				$item = "id";
				$valueProductId = $value["id"];
				$order = "id";

				$getProduct = ProductsModel::mdlShowProducts($tableProducts, $item, $valueProductId, $order);

				$item1a = "sales";
				$value1a = $getProduct["sales"] - $value["quantity"];

				$newSales = ProductsModel::mdlUpdateProduct($tableProducts, $item1a, $value1a, $valueProductId);

				$item1b = "stock";
				$value1b = $value["quantity"] + $getProduct["stock"];

				$stockNew = ProductsModel::mdlUpdateProduct($tableProducts, $item1b, $value1b, $valueProductId);

			}

			/*=============================================
			Delete Sale
			=============================================*/

			$answer = ModelSales::mdlDeleteSale($table, $_GET["idSale"]);

			if($answer == "ok"){

				echo'<script>

				swal({
					  type: "success",
					  title: "The sale has been deleted succesfully",
					  showConfirmButton: true,
					  confirmButtonText: "Close",
					  closeOnConfirm: false
					  }).then((result) => {
								if (result.value) {

								window.location = "sales";

								}
							})

				</script>';

			}		
		}

	}

	/*=============================================
	DATES RANGE
	=============================================*/	

	static public function ctrSalesDatesRange($initialDate, $finalDate){

		$table = "sales";

		$answer = ModelSales::mdlSalesDatesRange($table, $initialDate, $finalDate);

		return $answer;
		
	}

	/*=============================================
	DOWNLOAD EXCEL
	=============================================*/

	public function ctrDownloadReport(){

		if(isset($_GET["report"])){

			$table = "sales";

			if(isset($_GET["initialDate"]) && isset($_GET["finalDate"])){

				$sales = ModelSales::mdlSalesDatesRange($table, $_GET["initialDate"], $_GET["finalDate"]);

			}else{

				$item = null;
				$value = null;

				$sales = ModelSales::mdlShowSales($table, $item, $value);

			}

			/*=============================================
			WE CREATE EXCEL FILE
			=============================================*/

			$name = $_GET["report"].'.xls';

			header('Expires: 0');
			header('Cache-control: private');
			header("Content-type: application/vnd.ms-excel"); // Excel file
			header("Cache-Control: cache, must-revalidate"); 
			header('Content-Description: File Transfer');
			header('Last-Modified: '.date('D, d M Y H:i:s'));
			header("Pragma: public"); 
			header('Content-Disposition:; filename="'.$name.'"');
			header("Content-Transfer-Encoding: binary");

			echo utf8_decode("<table border='0'> 

					<tr> 
					<td style='font-weight:bold; border:1px solid #eee;'>Code</td> 
					<td style='font-weight:bold; border:1px solid #eee;'>Seller</td>
					<td style='font-weight:bold; border:1px solid #eee;'>Quantity</td>
					<td style='font-weight:bold; border:1px solid #eee;'>Products</td>
					<td style='font-weight:bold; border:1px solid #eee;'>Tax</td>
					<td style='font-weight:bold; border:1px solid #eee;'>NetPrice</td>		
					<td style='font-weight:bold; border:1px solid #eee;'>TOTAL</td>		
					<td style='font-weight:bold; border:1px solid #eee;'>Method of payment</td	
					<td style='font-weight:bold; border:1px solid #eee;'>Date</td>		
					</tr>");

			foreach ($sales as $row => $item){

				$Seller = ControllerUsers::ctrShowUsers("id", $item["idSeller"]);

			 echo utf8_decode("<tr>
			 			<td style='border:1px solid #eee;'>".$item["code"]."</td> 
			 			<td style='border:1px solid #eee;'>".$Seller["name"]."</td>
			 			<td style='border:1px solid #eee;'>");

			 	$products =  json_decode($item["products"], true);

			 	foreach ($products as $key => $valueproducts) {
			 			
			 			echo utf8_decode($valueproducts["quantity"]."<br>");
			 		}

			 	echo utf8_decode("</td><td style='border:1px solid #eee;'>");	

		 		foreach ($products as $key => $valueproducts) {
			 			
		 			echo utf8_decode($valueproducts["description"]."<br>");
		 		
		 		}

		 		echo utf8_decode("</td>
					<td style='border:1px solid #eee;'>Ksh ".number_format($item["tax"],2)."</td>
					<td style='border:1px solid #eee;'>Ksh ".number_format($item["netPrice"],2)."</td>	
					<td style='border:1px solid #eee;'>Ksh ".number_format($item["totalPrice"],2)."</td>
					<td style='border:1px solid #eee;'>".$item["paymentMethod"]."</td>
					<td style='border:1px solid #eee;'>".substr($item["saledate"],0,10)."</td>		
		 			</tr>");

			}


			echo "</table>";

		}

	}


	/*=============================================
	Adding TOTAL sales
	=============================================*/

	public function ctrAddingTotalSales(){

		$table = "sales";

		$answer = ModelSales::mdlAddingTotalSales($table);

		return $answer;

	}

	/*=============================================
	DOWNLOAD XML
	=============================================*/

	/*static public function ctrDownloadXML(){

		if(isset($_GET["xml"])){


			$table = "sales";
			$item = "code";
			$value = $_GET["xml"];

			$sales = ModelSales::mdlShowSales($table, $item, $value);

			// PRODUCTS

			$productsList = json_decode($sales["products"], true);

			// Seller

			$tableSeller = "users";
			$item = "id";
			$value = $sales["idSeller"];

			$getSeller = UsersModel::mdlShowUsers($tableSeller, $item, $value);

			//http://php.net/manual/es/book.xmlwriter.php

			$objectXML = new XMLWriter();

			$objectXML->openURI($_GET["xml"].".xml"); //XML file creation

			$objectXML->setIndent(true); //gets a boolean value to stablish if the different XML node leves must be indented or not.

			$objectXML->setIndentString("\t"); // caracter \t, it means to tab

			$objectXML->startDocument('1.0', 'utf-8');// document start
			
			// $objectXML->startElement("mainTag");// Beginning of root node

			// $objectXML->writeAttribute("mainAttributeTag", "value main Attribute Tag"); // main Attribute Tag

			// 	$objectXML->startElement("internalTag");// Beginning of child node

			// 		$objectXML->writeAttribute("InternalTagAttribute", "value Internal tag attribute"); // Internal tag attribute

			// 		$objectXML->text("Internal text");// Beginning of child node
			
			// 	$objectXML->endElement(); // End of child node 
			
			// $objectXML->endElement(); // End of root node


			$objectXML->writeRaw('<fe:Invoice xmlns:fe="http://www.dian.gov.co/contratos/facturaelectronica/v1" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:clm54217="urn:un:unece:uncefact:codelist:specification:54217:2001" xmlns:clm66411="urn:un:unece:uncefact:codelist:specification:66411:2001" xmlns:clmIANAMIMEMediaType="urn:un:unece:uncefact:codelist:specification:IANAMIMEMediaType:2003" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2" xmlns:sts="http://www.dian.gov.co/contratos/facturaelectronica/v1/Structures" xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.dian.gov.co/contratos/facturaelectronica/v1 ../xsd/DIAN_UBL.xsd urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2 ../../ubl2/common/UnqualifiedDataTypeSchemaModule-2.0.xsd urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2 ../../ubl2/common/UBL-QualifiedDatatypes-2.0.xsd">');

			$objectXML->writeRaw('<ext:UBLExtensions>');

			foreach ($productsList as $key => $value) {
				
				$objectXML->text($value["description"].", ");
			
			}		

			$objectXML->writeRaw('</ext:UBLExtensions>');

			$objectXML->writeRaw('</fe:Invoice>');

			$objectXML->endDocument(); // End document

			return true;	
		}

	}
	*/

}