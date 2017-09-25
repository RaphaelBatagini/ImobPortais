<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://brainmade.com.br
 * @since      1.0.0
 *
 * @package    Imob_Portais
 * @subpackage Imob_Portais/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Imob_Portais
 * @subpackage Imob_Portais/admin
 * @author     Raphael Batagini <raphabatagini@gmail.com>
 */
class Imob_Portais_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Imob_Portais_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Imob_Portais_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/imob-portais-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Imob_Portais_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Imob_Portais_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/imob-portais-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Recover all properties
	 *
	 * @since    1.0.0
	 */
	public function load_properties() {
		$query = new WP_Query(array(
		    'post_type' => 'estate_property',
		    'post_status' => 'publish',
		    'posts_per_page' => -1
		));

		$properties = [];

		while ($query->have_posts()) {
		    $query->the_post();

		    //get property images
			$post_attachments   = get_posts([
				'numberposts' => -1,
			    'post_type' => 'attachment',
			    'post_mime_type' => 'image',
			    'post_parent' => get_the_ID(),
			    'post_status' => null,
			    'exclude' => get_post_thumbnail_id(),
			    'orderby' => 'menu_order',
			    'order' => 'ASC'
			]);

			$images = [];

			$images[] = [
				'url' 		=> wp_get_attachment_url(get_post_thumbnail_id()),
		    	'featured'	=> true
			];

			foreach ($post_attachments as $key => $image) {
				$images[] = [
					'url' 		=> wp_get_attachment_image_src($image->ID, 'full')[0],
					'featured' 	=> false
				];
			}

		    $properties[get_the_ID()] = [
		    	'title' 			=> get_the_title(),
		    	'price' 			=> get_post_meta(get_the_ID(), 'property_price', true),
		    	'transaction_type' 	=> get_the_terms(get_the_ID(), 'property_action_category')[0]->slug,
		    	'category'			=> get_the_terms(get_the_ID(), 'property_category')[0]->name,
		    	'featured' 			=> get_post_meta(get_the_ID(), 'prop_featured')[0] == 0 ? false : true,
		    	'created_at' 		=> get_the_date('Y-m-d\TH:i:s'),
		    	'modified_at'		=> get_the_modified_date('Y-m-d\TH:i:s'),
		    	'permalink'			=> get_permalink(),
		    	'images'			=> $images,
		    	'video_type'		=> get_post_meta(get_the_ID(), 'embed_video_type', true),
		    	'video'				=> get_post_meta(get_the_ID(), 'embed_video_id', true),
		    	'property_cat'		=> get_the_category(),
		    	'description'		=> get_the_content(),
		    	'size'				=> get_post_meta(get_the_ID(), 'property_size', true),
		    	'bedrooms'			=> get_post_meta(get_the_ID(), 'property_bedrooms', true),
		    	'bathrooms'			=> get_post_meta(get_the_ID(), 'property_bathrooms', true),
		    	'garage'			=> get_post_meta(get_the_ID(), 'vagas', true),
		    	'country'			=> get_post_meta(get_the_ID(), 'property_country', true),
		    	'state'				=> get_post_meta(get_the_ID(), 'property_county', true),
		    	'city'				=> get_post_meta(get_the_ID(), 'property_city', true),
		    	'neighborhood'		=> get_post_meta(get_the_ID(), 'property_area', true),
		    	'address'			=> get_post_meta(get_the_ID(), 'property_address', true),
		    	'postal_code'		=> get_post_meta(get_the_ID(), 'property_zip', true),
		    	'latitude'			=> get_post_meta(get_the_ID(), 'property_latitude', true),
		    	'longitude'			=> get_post_meta(get_the_ID(), 'property_longitude', true)
		    ];
		}

		return $properties;
	}

	public function get_vivareal_category($site_category)
	{
		switch ($site_category) {
			case 'Apartamentos':
				return 'Residential / Apartment';
				break;

			case 'Apartamento Duplex':
				return 'Residential / Apartment';
				break;
			
			case 'Casa em Condomínios':
				return 'Residential / Condo';
				break;

			case 'Casas':
				return 'Residential / Home';
				break;

			case 'Escritórios':
				return 'Commercial / Office';
				break;

			case 'Imóveis Comerciais':
				return 'Commercial / Building';
				break;

			case 'Industrial':
				return 'Commercial / Industrial';
				break;

			case 'Terreno':
				return 'Commercial / Land Lot';
				break;

			default:
				# code...
				break;
		}
	}

	public function generate_vivareal_xml()
	{
		$properties = $this->load_properties();

		$xml = '<?xml version="1.0" encoding="UTF-8"?>
					<ListingDataFeed xmlns="http://www.vivareal.com/schemas/1.0/VRSync" 
					                 xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" 
					                 xsi:schemaLocation="http://www.vivareal.com/schemas/1.0/VRSync  http://xml.vivareal.com/vrsync.xsd">
					  	<Header>
					        <Provider>' . get_bloginfo('name') . '</Provider>
					        <Email>' . get_bloginfo('admin_email') . '</Email>
					        <ContactName>Caio Almeida</ContactName>
					        <PublishDate>' . date('Y-m-d\TH:i:s') . '</PublishDate>
					        <Logo>http://caioalmeida.in9ti.com/wp-content/uploads/2017/04/logo-site02.png</Logo>
					        <Telephone>(19) 98248-0775</Telephone>
					  	</Header>
					<Listings>';

		foreach ($properties as $id => $property) {
			$xml .= '<Listing>
					        <ListingID>' . $id . '</ListingID>
					        <Title><![CDATA[ ' . $property['title'] . ' ]]></Title>
					        <TransactionType>' . ($property['transaction_type'] == 'venda' ? 'For Sale' : 'For Rent') . '</TransactionType>
					        <Featured>' . $property['featured'] . '</Featured>
					        <ListDate>' . $property['created_at'] . '</ListDate>
					        <LastUpdateDate>' . $property['modified_at'] . '</LastUpdateDate>
					        <DetailViewUrl>' . $property['permalink'] . '</DetailViewUrl>
					        <Media>';

								if ($property['video_type'] == 'youtube') {
									$xml .=	'<Item medium="video">https://www.youtube.com/'.$property['video'].'</Item>';	
								}
								
								foreach ($property['images'] as $key => $img) {
									$xml .= PHP_EOL . '<Item medium="image" caption="nome imagem"' . ($img['featured'] ? 'primary="true"' : '') . '>' . $img['url'] . '</Item>';
								}

			$xml .=	        '</Media>
					        <Details>
					            <PropertyType>' . $this->get_vivareal_category($property['category']) . '</PropertyType>
					            <Description><![CDATA[ ' . $property['description'] . ' ]]>
					            </Description>
					            <ListPrice currency="BRL">' . $property['price'] . '</ListPrice>
					            <LivingArea unit="square metres">' . $property['size'] . '</LivingArea>
					            <Bedrooms>' . $property['bedrooms'] . '</Bedrooms>
					            <Bathrooms>' . $property['bathrooms'] . '</Bathrooms>
					            <Garage type="Parking Space">' . $property['garage'] . '</Garage>
					        </Details>
					        <Location displayAddress="Neighborhood">
					            <Country abbreviation="BR">' . $property['country'] . '</Country>
					            <State abbreviation="SP">' . $property['state'] . '</State>
					            <City>' . $property['city'] . '</City>
					            <Neighborhood>' . $property['neighborhood'] . '</Neighborhood>
					            <Address>' . $property['address'] . '</Address>
					            <PostalCode>' . $property['postal_code'] . '</PostalCode>
					            <Latitude>' . $property['latitude'] . '</Latitude>
					            <Longitude>' . $property['longitude'] . '</Longitude>
					        </Location>
					        <ContactInfo>
					            <Name>' . get_bloginfo('name') . '</Name>
					            <Email>' . get_bloginfo('admin_email') . '</Email>
					            <Website>' . get_bloginfo('url') . '</Website>
					            <Logo>http://caioalmeida.in9ti.com/wp-content/uploads/2017/04/logo-site02.png</Logo>
					            <OfficeName>' . get_bloginfo('name') . '</OfficeName>
					            <Telephone>(19) 98248-0775</Telephone>
					            <Location>
					                  <Country abbreviation="BR">Brasil</Country>
					                  <State abbreviation="SP">Sao Paulo</State>
					                  <City>São Paulo</City>
					                  <Neighborhood>Campeche</Neighborhood>
					                  <Address>Rua Guarandi, 90</Address> 
					                  <PostalCode>03709-000</PostalCode>
					                  <Latitude>-25.4188</Latitude>
					                  <Longitude>-49.3386</Longitude>
					            </Location>
					        </ContactInfo>
					    </Listing>';
		}

		$xml .=		'</Listings>
					</ListingDataFeed>';

		$xml_file = fopen(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'vivareal.xml', 'w');
		fwrite($xml_file, $xml);
		fclose($xml_file);
	}

	public function get_zap_property_type($site_category)
	{
		switch ($site_category) {
			case 'Apartamentos':
				return 'Apartamento';
				break;

			case 'Apartamento Duplex':
				return 'Apartamento';
				break;
			
			case 'Casa em Condomínios':
				return 'Casa';
				break;

			case 'Casas':
				return 'Casa';
				break;

			case 'Escritórios':
				return 'Comercial/Industrial';
				break;

			case 'Imóveis Comerciais':
				return 'Comercial/Industrial';
				break;

			case 'Industrial':
				return 'Comercial/Industrial';
				break;

			case 'Terreno':
				return 'Terreno';
				break;

			default:
				# code...
				break;
		}
	}

	public function get_zap_property_subtype($site_category)
	{
		switch ($site_category) {
			case 'Apartamentos':
				return 'Apartamento Padrão';
				break;

			case 'Apartamento Duplex':
				return 'Apartamento Padrão';
				break;
			
			case 'Casa em Condomínios':
				return 'Casa de Condomínio';
				break;

			case 'Casas':
				return 'Casa Padrão';
				break;

			case 'Escritórios':
				return 'Conjunto Comercial/Sala';
				break;

			case 'Imóveis Comerciais':
				return 'Loja/Salão';
				break;

			case 'Industrial':
				return 'Indústria';
				break;

			case 'Terreno':
				return 'Terreno Padrão';
				break;

			default:
				# code...
				break;
		}
	}

	public function get_zap_property_category($site_category)
	{
		switch ($site_category) {
			case 'Apartamentos':
				return 'Padrão';
				break;

			case 'Apartamento Duplex':
				return 'Duplex';
				break;
			
			case 'Casa em Condomínios':
				return 'Térrea';
				break;

			case 'Casas':
				return 'Térrea';
				break;

			case 'Escritórios':
				return 'Padrão';
				break;

			case 'Imóveis Comerciais':
				return 'Padrão';
				break;

			case 'Industrial':
				return 'Padrão';
				break;

			case 'Terreno':
				return 'Padrão';
				break;

			default:
				# code...
				break;
		}
	}

	public function generate_zap_xml() {
		$properties = $this->load_properties();

		$xml = '<?xml version="1.0" encoding="UTF-8"?>
					<ListingDataFeed xmlns="http://www.vivareal.com/schemas/1.0/VRSync" 
					                 xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" 
					                 xsi:schemaLocation="http://www.vivareal.com/schemas/1.0/VRSync  http://xml.vivareal.com/vrsync.xsd">
					  	<Header>
					        <Provider>' . get_bloginfo('name') . '</Provider>
					        <Email>' . get_bloginfo('admin_email') . '</Email>
					        <ContactName>Caio Almeida</ContactName>
					        <PublishDate>' . date('Y-m-d\TH:i:s') . '</PublishDate>
					        <Logo>http://caioalmeida.in9ti.com/wp-content/uploads/2017/04/logo-site02.png</Logo>
					        <Telephone>(19) 98248-0775</Telephone>
					  	</Header>
					<Listings>';

		foreach ($properties as $id => $property) {
			$xml .= PHP_EOL . '<Listing>
					        <CodigoImovel>' . $id . '</CodigoImovel>
					        <Arquivos>';
								
								foreach ($property['images'] as $key => $img) {
									$xml .= PHP_EOL . '<NomeArquivo>' . end(explode('/', $img['url'])) . '</NomeArquivo>';
									$xml .= PHP_EOL . '<URLArquivo>' . $img['url'] . '</URLArquivo>';
									$featured = $img['featured'] ? 1 : 0;
									$xml .= PHP_EOL . '<Principal>' . $featured . '</Principal>';
								}

			$xml .=	       	PHP_EOL . '</Arquivos>
				            <TipoImovel>' . $this->get_zap_property_type($property['category']) . '</TipoImovel>
				            <SubTipoImovel>' . $this->get_zap_property_subtype($property['category']) . '</SubTipoImovel>
				            <CategoriaImovel>' . $this->get_zap_property_category($property['category']) . '</CategoriaImovel>';

				            //property video
				            if ($property['video_type'] == 'youtube') {
				            	$xml .= '<Videos>';
				            		$xml .= '<Video>';
				            			$xml .=	'<Url>https://www.youtube.com/watch?v='.$property['video'].'</Url>';	
				            		$xml .= '</Video>';
				            	$xml .= '</Videos>';
							}
				            
				            //transaction tye
				            if ($property['transaction_type'] == 'venda') {
				            	$xml .= PHP_EOL . '<PrecoVenda>' . number_format($property['price'], 0, ',', '.') . '</PrecoVenda>';
				            } else {
				            	$xml .= PHP_EOL . '<PrecoLocacao>' . number_format($property['price'], 0, ',', '.') . '</PrecoLocacao>';
				            }

				            //property area
				            if (
				            	$this->get_zap_property_type($property['category']) == 'Terreno'
				            ) {
				            	$xml .= PHP_EOL . '<AreaTotal>' . $property['size'] . '</AreaTotal>
				            			<AreaUtil>0</AreaUtil>';
				            } else {
				            	$xml .= PHP_EOL . '<AreaUtil>' . $property['size'] . '</AreaUtil>';
				            }

			$xml .=        PHP_EOL . '<UnidadeMetrica>M2</UnidadeMetrica>
				            <QtdDormitorios>' . $property['bedrooms'] . '</QtdDormitorios>
				            <QtdBanheiros>' . $property['bathrooms'] . '</QtdBanheiros>
				            <QtdVagas>' . $property['garage'] . '</QtdVagas>
				            <UF>' . $property['state'] . '</UF>
				            <Cidade>' . $property['city'] . '</Cidade>
				            <Bairro>' . $property['neighborhood'] . '</Bairro>
				            <CEP>' . $property['postal_code'] . '</CEP>
					    </Listing>';
		}

		$xml .=		'</Listings>
					</ListingDataFeed>';

		$xml_file = fopen(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'zap.xml', 'w');
		fwrite($xml_file, $xml);
		fclose($xml_file);
	}

}
