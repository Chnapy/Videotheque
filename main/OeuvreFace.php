<?php

use thcolin\SensCritiqueAPI\Document;
use thcolin\SensCritiqueAPI\Core\API;
use thcolin\SensCritiqueAPI\Exceptions\DocumentParsingException;

class OeuvreFace {

	use Document {
		Document::__construct as constr;
	}

	private $url;
	private $id;
	private $type;

	public function __construct($uri) {
		$this->constr($uri);
		$this->url = $this->__toString();
		$u = explode("/", $this->uri);
		$this->id = intval($u[2]);
		$this->type = $u[0];
		$this->checkConnection();
	}

	function checkConnection() {
		SC::getClient()->setConnecte(boolval($this->find('html body .lahe-header .lahe-topBar-userMenu .profileAction')));
	}

	public function getMaNote() {
		$is_connecte = SC::getClient()->isConnecte();
		if (!$is_connecte) {
			return array('is_connecte' => $is_connecte);
		}

		if ($elements = $this->find('.pvi-hero .elrs-stars[data-product-original-rating]')) {
			$note = intval($elements[0]->getAttribute("data-product-original-rating", ""));
		} else {
			$note = 0;
		}

		$envie = $coeur = $encours = $critique = false;
		$actions = $this->find('.pvi-hero .pvi-product-scaction.active');
		foreach ($actions as $a) {
			switch ($a->getAttribute("data-sc-action")) {
				case 'wish-list':
					$envie = true;
					break;
				case 'recommend':
					$coeur = true;
					break;
				case 'current':
					$encours = true;
					break;
				default:
					if ($a->getAttribute('data-rel') === 'bt-review') {
						$critique = $a->getAttribute("href", false);
					}
			}
		}
		$vu = $note > 0 || $coeur || $critique;
		return array(
			'is_connecte' => $is_connecte,
			'vu' => $vu,
			'note' => $note,
			'envie' => $envie,
			'coeur' => $coeur,
			'encours' => $encours,
			'critique' => $critique,
		);
	}

	public function getMoyenne() {
		if ($elements = $this->find('.pvi-hero .pvi-scrating-value[itemprop="ratingValue"]')) {
			return $elements[0]->text();
		} else {
			return '';
//			throw new DocumentParsingException('moyenne');
		}
	}

	public function getMainBigAffiche() {
		if ($elements = $this->find('.pvi-hero .pvi-hero-figure a.lightview')) {
			return $elements[0]->getAttribute("href", "");
		} else {
			throw new DocumentParsingException('main_big_affiche');
		}
	}

	public function getBackAffiche() {
		if ($elements = $this->find('.pvi-hero[style]')) {
			$item = $elements[0]->getAttribute("style", "");
			return strstr(strstr($item, 'https://'), ');', true);
		} else {
			return "";
		}
	}

	public function getType2() {
		if ($elements = $this->find('.d-grid-main li.pvi-productDetails-item h2')) {
			$item = strtolower($elements[0]->text());
			return (strstr($item, "anim") !== FALSE) ? "anime" : "";
		} else {
			throw new DocumentParsingException('is_anime');
		}
	}

	public function hasSaga() {
		return $this->findNextNodeByText($this->find('aside.d-grid-aside')[0], 'Du même groupe') ? true : false;
	}

	public function getSaga($b_id) {
		$ret = array();
		$elements = $this->findNextNodeByText($this->find('aside.d-grid-aside')[0], 'Du même groupe'); //ul
//		return $elements->html();
		if ($elements) {
			$titre = $elements->find('ul .eas-item a')[0];
			$ret['titre'] = array('lien' => 'http://' . API::DOMAIN . $titre->getAttribute("href", ""), 'text' => $titre->text());
			$ret['content'] = array(array(
					'sc_id' => $this->id,
					'b_id' => $b_id,
					'affiche' => $this->getMainBigAffiche()
			));
			foreach ($elements->find('.elmv-miniview') as $it) {
				$item = $this->getItemSaga($it);
				if ($item) {
					$ret['content'][] = $item;
				}
			}
		}
		return $ret;
	}

	private function getItemSaga($it) {

		$a = $it->find('.elmv-miniview-content a.elmv-miniview-title2')[0];
		$href = $a->getAttribute("href");
		$id = intval(basename($href));
		$affiche = $it->find('figure a img')[0]->getAttribute('src', '');

		foreach (BIBLIO::$biblio as $b) {
			$url = $b['sc'];
			$b_id = intval(basename($url));
			if ($id !== $b_id) {
				continue;
			}

			$b_true_id = $b['id'];


			return array(
				'sc_id' => $id,
				'b_id' => $b_true_id,
				'affiche' => $affiche
			);
		}
		$a = $it->find('.elmv-miniview-content .elmv-miniview-title2')[0];
		$titre = $a->text();
		$url = 'http://' . API::DOMAIN . $a->getAttribute('href', '');

		return array(
			'sc_id' => $id,
			'b_id' => null,
			'affiche' => $affiche,
			'titre' => $titre,
			'url' => $url
		);
	}

	public function getTrailer() {
		if ($elements = $this->find('.d-grid-main .pvi-trailer iframe')) {
			return $elements[0]->getAttribute('src');
		} else {
			return '';
		}
	}

}
