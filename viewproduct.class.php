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
		$products_ids = array();
		$products = '';
	
		$q = $this->modx->newQuery('modResource', array('parent:=' => $parent));
		$q->select(array(
			'modResource.id as docID',
			'pagetitle',
			'isfolder'
		));
		$q->limit(1000);

		$q->prepare();
		$q->stmt->execute();
		$res = $q->stmt->fetchAll(PDO::FETCH_ASSOC);
		
		foreach ($res as $v) {			
			$products_ids[$parent][] = $v;
			if($v['isfolder'] == 1){
				$products .= '<h3 class="text-center clear">'.$v['pagetitle'].'</h3>'.$this->getList($v['docID']);
			}else{
				$tpl = '<div class="product shk-item left">
					<div class="product-preview">
						<img src="[[+preview]]" alt="" title="" />
					</div>
					<div class="product-icons">
						
					</div>
					<div class="product-title">
						[[+pagetitle]]<br /><strong>[[+weight]] гр.</strong>
					</div>
					<a href="[[~[[+id]]]]" class="product-link js-open-product no-ajaxy" role="button" data-id="[[+id]]" data-toggle="modal-product"></a>
					<form action="[[~[[+id]]? &scheme=`abs`]]" method="post">
						<input type="hidden" name="shk-id" value="[[+id]]" />
						<input type="hidden" name="shk-name" value="[[+pagetitle]]" />
						<input type="hidden" name="shk-count" value="1" />

						<button type="submit" name="shk-submit" class="product-tocart shk-but">
							<span class="product-oldprice">[[+oldprice:notempty=`[[+oldprice]] <small>р.</small>`]]</span>
							<span class="product-price"><span class="shk-price" id="stuff_[[+id]]_price">[[+price:num_format]]</span> <small>р.</small></span>
						</button>
					</form>
				</div>';
				if ($tvPreview = $this->modx->getObject('modTemplateVarResource', array('tmplvarid' => 2, 'contentid' => $v['docID']))) {
					$tvPreview = $tvPreview->get('value');
				}
				if ($tvWeight = $this->modx->getObject('modTemplateVarResource', array('tmplvarid' => 1, 'contentid' => $v['docID']))) {
					$tvWeight = $tvWeight->get('value');
				}
				if ($tvOldprice = $this->modx->getObject('modTemplateVarResource', array('tmplvarid' => 3, 'contentid' => $v['docID']))) {
					$tvOldprice = $tvOldprice->get('value');
				}
				if ($tvPrice = $this->modx->getObject('modTemplateVarResource', array('tmplvarid' => 4, 'contentid' => $v['docID']))) {
					$tvPrice = $tvPrice->get('value');
				}
				// if ($tvPrice = $this->modx->getObject('modTemplateVarResource', array('tmplvarid' => 1, 'contentid' => $v['docID']))) {
					// $tvPrice = $tvPrice->get('value');
				// }
				
				$props = array(
					'id' => $v['docID'],
					'pagetitle' => $v['pagetitle'],
					'preview' => $tvPreview,
					'weight' => $tvWeight,
					'oldprice' => $tvOldprice,
					'price' => $tvPrice
				);
				
				//$pageTv = $this->modx->getObject('modResource', $v['modResource_id']);
				
				
				$uniqid = uniqid();
				$chunk = $this->modx->newObject('modChunk', array('name' => "{tmp}-{$uniqid}"));
				$chunk->setCacheable(false);
				$products .= $chunk->process($props, $tpl);
			}		
		}
		//print_r($products_ids);
				// switch ($v['modResource_isfolder']){
					// case '1':
						// $products .= '<h3 class="text-center clear">'.$v['modResource_pagetitle'].'</h3>'.$this->getList($v['modResource_id']);
					// break;
					// default:
						// $source = 'prod';
						// $page = $this->modx->getObject('modResource', $v['modResource_id']);
						// print_r($page->getTVValue('tv.preview'));
						// $properties = array(
							// 'id' => $v['modResource_id'],
							// 'pagetitle' => $v['modResource_pagetitle'],
							// 'alias' => $v['modResource_alias'],
							// 'tv.price' => $page->getTVValue('price'),
							// 'tv.oldprice' => $page->getTVValue('oldprice'),
							// 'tv.preview' => $page->getTVValue('preview'),
							// 'tv.check_hit' => $page->getTVValue('check_hit'),
							// 'tv.check_hot' => $page->getTVValue('check_hot'),
							// 'tv.check_eco' => $page->getTVValue('check_eco'),
						// );
						// $products .= $this->modx->getChunk($source, $properties);						
					// break;
				// }
		// }
		return $products;
	}
	
	function getChunk($source, $properties = null){
		
	}

}

?>