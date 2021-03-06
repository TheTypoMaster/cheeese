<?php 

namespace Main\CommonBundle\Services\Session;

use Symfony\Component\HttpFoundation\Session\Session;

class ServiceSession 
{
	/**
	 *
	 * 
	 */
	private $session;
	
	public function __construct(Session $session)
	{
		$this->session = $session;
		//TODO : set le lifetime
	}
	
	/**
	 * 
	 * @param unknown $category
	 * @param unknown $town_code
	 * @param unknown $town_name
	 * @param unknown $day
	 */
	public function setSearchArgs($category, $town_code=null, $town_name=null, $day=null, $min=null, $max=null)
	{
		$this->session->set('front_search', array(
				'category'  	=> $category,
				'town'			=> $town_code,
				'town_text'		=> $town_name,
				'day'			=> $day,
				'min'			=> $min,
				'max'			=> $max
				));
	}
	
	/**
	 * 
	 */
	public function getSearchArgs()
	{
		return $this->session->get('front_search');
	}
	
	/**
	 * 
	 * @param unknown $devis
	 */
	public function setDesiredDevis($devis)
	{
		$this->session->set('front_devis', $devis);
	}
	
	/**
	 * 
	 */
	public function getDesiredDevis()
	{
		return $this->session->get('front_devis');
	}
	
	/**
	 * Si les variables obligatoires sont la
	 * @return boolean
	 */
	public function isSearchVariablesSet() 
	{
		return $this->session->has('front_search');
	}

	public function successFlashMessage($text)
	{
		$this->session->getFlashBag()->add('success', $text);

	}

	public function errorFlashMessage()
	{
		$this->session->getFlashBag()->add('danger', 'flash.message.error');
	}

	public function errorFlashMessageCustom($message)
	{
		$this->session->getFlashBag()->add('danger', $message);
	}

	public function remove($text)
	{
		if ($this->session->has($text)) {
			$this->session->remove($text);
		}
	}
}