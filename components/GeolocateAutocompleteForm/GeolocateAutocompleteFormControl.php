<?php

namespace Wame\Geolocate\Components;

use Nette\Utils\Html;
use Nette\Forms\Controls\BaseControl;
use Nette\Forms\Container;
use Wame\LocationModule\Entities\AddressEntity;


class GeolocateAutocompleteFormControl extends BaseControl
{
	/** @var bool */
	private static $registered = false;

    /** @var string */
    private $labelName;

    /** @var AddressEntity */
    private $defaultValue;

    /** @var string */
    private $type;

    /** @var string */
    private $url;


	public function __construct($label) 
    {
		parent::__construct($label);

        $this->labelName = $label;
	}
    
    
    public function getType()
    {
        if ($this->type) {
            return $this->type;
        } else {
            return '(regions)';
        }
    }
    
    
    public function getUrl()
    {
        if ($this->url) {
            return $this->url;
        } else {
            return '/location/address?do=createAddressFromGoogleMapApi';
        }
    }
    
    
    public function setType($type)
    {
        $this->type = $type;
        
        return $this;
    }
    
    
    public function setUrl($url)
    {
        $this->url = $url;
        
        return $this;
    }
    
	
    /**
     * Set default value
     * 
     * @param AddressEntity $value
     * @return \Wame\AddressAutocompleteFormControl\Controls\AddressAutocompleteFormControl
     * @throws Exception
     */
    public function setDefaultValue($value)
    {
        $this->defaultValue = $value;

        if ($value instanceof AddressEntity) {
            $value = $value->getId();
        } else {
            throw new \Exception(_('Value addGeolocateAutocomplete must be instance of AddressEntity.'));
        }
        
		$this->setValue($value);
		
		return $this;
    }
    
    
    /**
     * Prepare input
     * 
     * @return \Nette\Utils\Html
     */
    public function getControl()
	{
		$control = parent::getControl();

        $control->addAttributes([
					'type' => 'hidden',
					'name' => $this->getHtmlName()
				])
                ->setValue($this->getValue());
        
        $input = Html::el('input')
                ->setClass('form-control')
                ->setData('input', $this->getHtmlId())
                ->setData('type', $this->getType())
                ->setData('url', $this->getUrl())
                ->addAttributes([
                    'id' => 'google-map-api-autocomplete',
                    'autocomplete' => 'off'
                ]);
        
        if ($this->getValue()) {
            $address = $this->defaultValue;
            
            $input->setStyle('display: none;');
            
            $close = Html::el('a')
                        ->setHref('#')
                        ->setClass('btn btn-sm btn-link pull-right google-map-api-autocomplete-close')
                        ->setHtml(Html::el('span')->setClass('fa fa-times-circle'));
            
            $input .= Html::el('div')
                        ->setClass('well well-sm')
                        ->setHtml($address->city->title . ', ' . $address->state->title . $close);
        }

        return Html::el('div')->setClass('google-map-api-autocomplete')->setHtml($input) . $control;
	}

	
	public static function register($method = 'addGeolocateAutocomplete')
	{
		if (static::$registered) {
			throw new Nette\InvalidStateException(_('Geolocate autocomplete form control already registered.'));
		}
		
		static::$registered = true;
		
		$class = function_exists('get_called_class') ? get_called_class() : __CLASS__;
		
		Container::extensionMethod(
			$method, function (Container $container, $name, $label = null) use ($class) 
            {
				$component[$name] = new $class($label);
				
				$container->addComponent($component[$name], $name);
                
				return $component[$name];
			}
		);
	}
	
}