<?php

namespace thcolin\SensCritiqueAPI\Core;

use DiDom\Document;
use jyggen\Curl\Dispatcher;
use jyggen\Curl\Request;
use thcolin\SensCritiqueAPI\Exceptions\URIException;
use thcolin\SensCritiqueAPI\Exceptions\RedirectException;
use thcolin\SensCritiqueAPI\Exceptions\JSONUnvalidException;
use OeuvreFace;

class API {

	const DOMAIN = 'www.senscritique.com';

	public function getOeuvreFaceByURI($args, $options = array()) {
		$uris = (is_array($args) ? $args : [$args]);
		$dp = new Dispatcher();
		$requests = $documents = [];

		foreach ($uris as $uri) {
			$r = new Request('http://' . self::DOMAIN . '/' . $uri);
			$r->setOption($options);
			$requests[] = $r;
			$dp->add(end($requests));
		}
		$dp->execute();

		foreach ($requests as $request) {
			$raw = substr($request->getRawResponse(), $request->getInfo(CURLINFO_HEADER_SIZE));

			if ($request->getResponse()->headers->get('location')) {
				throw new RedirectException();
			} else if (!$raw) {
				throw new URIException();
			}

			$documents[] = new OeuvreFace($raw);
		}

		return (is_array($args) ? $documents : $documents[0]);
	}

	public function getDocumentByURI($args, $options = array()) {
		$uris = (is_array($args) ? $args : [$args]);
		$dp = new Dispatcher();
		$requests = $documents = [];

		foreach ($uris as $uri) {
			$r = new Request('http://' . self::DOMAIN . '/' . $uri);
			$r->setOption($options);
			$requests[] = $r;
			$dp->add(end($requests));
		}
		$dp->execute();

		foreach ($requests as $request) {
			$raw = substr($request->getRawResponse(), $request->getInfo(CURLINFO_HEADER_SIZE));

			if ($request->getResponse()->headers->get('location')) {
				throw new RedirectException();
			} else if (!$raw) {
				var_dump('http://' . self::DOMAIN . '/' . $uri);
				throw new URIException();
			}

			$documents[] = new Document($raw);
		}

		return (is_array($args) ? $documents : $documents[0]);
	}

	public function getJSONByFullURL($url, $options = array()) {

		if (isset($options[CURLOPT_HTTPHEADER])) {
			$options[CURLOPT_HTTPHEADER][] = 'X-Requested-With: XMLHttpRequest';
		} else {
			$options[CURLOPT_HTTPHEADER] = ['X-Requested-With: XMLHttpRequest'];
		}
		$request = new Request($url);
		$request->setOption($options);
		$request->execute();

		$raw = substr($request->getRawResponse(), $request->getInfo(CURLINFO_HEADER_SIZE));
		$json = json_decode($raw, true);

		if (!$json OR ! $json['json']['success']) {
//			var_dump($json);
			throw new JSONUnvalidException($json);
		}

		return $json;
	}

	public function getJSONByFullURI($uri, $options = array()) {
		return $this->getJSONByFullURL('http://' . self::DOMAIN . '/' . $uri . '.json', $options);
	}

	public function getJSONByURI($uri, $options = array()) {
		return $this->getJSONByFullURI('sc2/' . $uri, $options);
	}

	public function getJSONByURIWithPOST($uri, $data, $options = array()) {
		if (is_array($data)) {
			$postData = '';
			foreach ($data as $k => $v) {
				$postData .= $k . '=' . $v . '&';
			}
			$data = rtrim($postData, '&');
		}

		$options[CURLOPT_POST] = count($data);
		$options[CURLOPT_POSTFIELDS] = $data;

		return $this->getJSONByURI($uri, $options);
	}

	public function getLocation($uri) {
		$request = new Request('http://' . self::DOMAIN . '/' . $uri);
		$request->execute();

		$location = $request->getResponse()->headers->get('location');

		if (!$location) {
			throw new URIException('No "Location" header found');
		} else if ($location == '/') {
			throw new URIException();
		} else {
			return $location;
		}
	}

}

?>
