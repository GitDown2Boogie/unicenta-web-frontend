<?php
/* 
* Takes a query string $_GET['id'] from Apache mod_rewrite and processes it to display the 
* coresponding category's products in an unordered list.
*
*	The incomming string is like:
*	'Mens/Accessories/Glasses/Sun glasses' (seperated by '/')
*	or
	'Category Standard' (Top level categories not seperates by '/')
*
*	SUDO Logic:
	
	//Check for $id = $_GET['id']

	//Process $id 
		//Explode it with the '/' seperator

	//If no '/' seperater, check if it's a parent category

*	//Show products based on category id.
*
*	Else, echo no products found
*
*/

echo '<hr />';
$query = str_replace(WEB_ROOT, '', $_GET['id']);

//Find LAST position of '/' in the string.
$lastPosition = strrpos($query, '/'); 


if(!$lastPosition)//If no '/' seperator presume parent category
{
	$query = $query;	
}else{//Strip last category from query string 

	$query = substr($query, $lastPosition + 1);
}

//Clean $query string:
$query = cleanString($dbc, $query);


//Find category id  in database:
$q = "SELECT ID FROM CATEGORIES WHERE NAME = '$query'";
$r = mysqli_query($dbc, $q);


if(mysqli_num_rows($r) == 1)
{

	list($catId) = mysqli_fetch_array($r);
	//Fetch products in specified categy into unordered list
	if($products =  getProductsinCategory($dbc, $catId, $products = array()))
	{	

		//Product list
		echo "\n<ul>";

		//Echo each product into a list	
		foreach($products as $product)
		{
			echo "\n<li class=\"product\">\n";
			echo '<h4 class="productTitle">' .  $product['NAME'] . "\n</h4>";
			//Product image
				//Link around image
				echo '<a href="' . getProductUrl($dbc, $product['ID']) . '">';
			echo '<img src="';
			echo BASE_URL . 'includes/getImage.php?id=' . $product['ID'] . '" width="100" height="100" /></a>';
			echo "\n<p class=\"productDesc\">" . $product['ATTRIBUTES'] . '</p>';
			
			//Print product price
			echo "\n" . '<div class="productPrice">&pound;' . $product['PRICESELL'] . '</div>';
			//End print product price
			
			echo '</li>';
			
			//Warn visitor if out-of-stock
			if(!instock($dbc, $product['ID']))
			{
				echo "\n" . '<p class="outOfStock">Currently out of stock.</p>';
			}//End if out-of-stock say so		
		}
		echo "\n</ul>"; //Terminate product list

	}else{
		echo 'No products in this category';
	}
	

}else{
	echo 'Category not found';
}//End if category not found in database, say category deso not exist'

