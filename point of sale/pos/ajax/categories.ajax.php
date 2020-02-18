<?php

require_once "../controllers/categories.controller.php";
require_once "../models/categories.model.php";

class AjaxCategories{

	/*=============================================
	EDIT CATEGORY
	=============================================*/	

	public $idCategory;

	public function ajaxEditCategory(){

		$item = "id";
		$value = $this->idCategory;

		$answer = ControllerCategories::ctrShowCategories($item, $value);

		echo json_encode($answer);

	}

		/*=============================================
	VALIDATE IF CATEGORY ALREADY EXISTS
	=============================================*/

	public $validateCategory;

	public function ajaxValidateCategory(){

		$item = "Category";
		$value = $this->validateCategory;

		$answer = ControllerCategories::ctrShowCategories($item, $value);

		echo json_encode($answer);

	}
}

/*=============================================
EDITAR CATEGORÍA
=============================================*/	
if(isset($_POST["idCategory"])){

	$category = new AjaxCategories();
	$category -> idCategory = $_POST["idCategory"];
	$category -> ajaxEditCategory();
}

/*=============================================
VALIDATE IF CATEGORY ALREADY EXISTS
=============================================*/


if (isset($_POST["validateCategory"])) {

	$valCategory = new AjaxCategories();
	$valCategory -> validateCategory = $_POST["validateCategory"];
	$valCategory -> ajaxValidateCategory();
}