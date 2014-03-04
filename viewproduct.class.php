<?php

class viewProduct{
	public $modx;
	public function __construct(modX &$modx, $config = array()){    
		$this->modx =& $modx;
		
		 $this->config = array_merge(array(
			'corePath' => $this->modx->getOption('core_path').'components/viewproduct/',
			'tplPath' => 'build/chanks/catalog/',			
		),$config);
	}
		
	function getList($parent){
		$products = array();		
		$itemTpl = $this->modx->getParser()->getElement('modChunk', 'productRow');
		$outputTpl = $this->modx->getParser()->getElement('modChunk', 'productOuter');
		
		$q = $this->modx->newQuery('modResource');
		$q->leftJoin('modTemplateVarResource', 'preview', array('modResource.id = preview.contentid', 'preview.tmplvarid = 2'));
		$q->leftJoin('modTemplateVarResource', 'weight', array('modResource.id = weight.contentid', 'weight.tmplvarid = 1'));
		$q->leftJoin('modTemplateVarResource', 'oldprice', array('modResource.id = oldprice.contentid', 'oldprice.tmplvarid = 3'));
		$q->leftJoin('modTemplateVarResource', 'price', array('modResource.id = price.contentid', 'price.tmplvarid = 4'));
		$q->leftJoin('modTemplateVarResource', 'check_hot', array('modResource.id = check_hot.contentid', 'check_hot.tmplvarid = 6'));
		$q->leftJoin('modTemplateVarResource', 'check_hit', array('modResource.id = check_hit.contentid', 'check_hit.tmplvarid = 8'));
		$q->leftJoin('modTemplateVarResource', 'check_eco', array('modResource.id = check_eco.contentid', 'check_eco.tmplvarid = 7'));
		$q->select(array(
		 'modResource.parent',
		 'modResource.id',
		 'modResource.pagetitle',
		 'preview.value as preview',
		 'weight.value as weight',
		 'oldprice.value as oldprice',
		 'price.value as price',
		 'check_hot.value as check_hot',
		 'check_hit.value as check_hit',
		 'check_eco.value as check_eco',
		 ));
		$q->where(array(
		 'modResource.published' => true,
		 'modResource.deleted' => false,
		 'modResource.template' => 4
		 ));
		$q->prepare();
		$q->stmt->execute();
		$res = $q->stmt->fetchAll(PDO::FETCH_ASSOC);
	
		
	
		foreach ($res as $v) {
			$icons = '';
			$class = '';
			isset($v['check_hot']) ? $icons .= '<span class="action-icon icon-hot"></span>' : '';
			isset($v['check_hit']) ? $icons .= '<span class="action-icon icon-hit"></span>' : '';			
			if(isset($v['check_eco'])){
				$icons .= '<span class="action-icon icon-eco"></span>';
				$class .= ' product-eco';
			}
			
			if(isset($v['oldprice'])){
				$v['oldprice'] = $v['oldprice'].'<small>р.</small>';
				if($v['oldprice'] > $v['price']) {
					$class .= ' product-sale';
					$icons .= '<span class="action-icon icon-sale"></span>';
				};
			}else{
				$v['oldprice'] = '';
			}		
			$v['price'] = number_format($v['price'], 0, ',', ' ');
			
			$v['icons'] = $icons;
			$v['class'] = $class;
			$itemTpl->setCacheable(false);
			$itemTpl->_processed = false;
			$products[$v['parent']] .= $itemTpl->process($v);	
		}
		$output = '';
		foreach( $products as $id => $childrens){
			$page = $this->modx->getObject('modResource', $id);
			$params = array(
				'pagetitle' => $page->pagetitle,
				'wrapper' => $childrens
			);
			$outputTpl->setCacheable(false);
			$outputTpl->_processed = false;
			$output .= $outputTpl->process($params);
		}
		return $output;
	}
	
	function getChunk($source, $properties = null){
		
	}

}

?>