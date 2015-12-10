<?php
function getCardCategories($limit = 10, $page = 1) {
		$method = "GET";
		$endpoint = endpointWith("/api/v1/categories", $method);
		global $verbose;
		checkAuth();
		
		$data = array("limit" => $limit,
	"page" => $page
		);
		
		if ($method != "GET") {
			$data = formattedParametersWithData($endpoint, $data);
		}
		
		$newPath = fillEndpointPathWithRequirements($endpoint, $data);
		$data = cleanEndpointRequirementsFromData($endpoint, $data);
		return sendGET(defaultHeader(), $data, $newPath);
}

function getCardCategory($category) {
		$method = "GET";
		$endpoint = endpointWith("/api/v1/categories/{category}", $method);
		global $verbose;
		checkAuth();
		
		$data = array("category" => $category
		);
		
		if ($method != "GET") {
			$data = formattedParametersWithData($endpoint, $data);
		}
		
		$newPath = fillEndpointPathWithRequirements($endpoint, $data);
		$data = cleanEndpointRequirementsFromData($endpoint, $data);
		return sendGET(defaultHeader(), $data, $newPath);
}

function getGiftCardsInCategory($category, $limit = 10, $page = 1) {
		$method = "GET";
		$endpoint = endpointWith("/api/v1/categories/{category}/giftcards", $method);
		global $verbose;
		checkAuth();
		
		$data = array("category" => $category,
	"limit" => $limit,
	"page" => $page
		);
		
		if ($method != "GET") {
			$data = formattedParametersWithData($endpoint, $data);
		}
		
		$newPath = fillEndpointPathWithRequirements($endpoint, $data);
		$data = cleanEndpointRequirementsFromData($endpoint, $data);
		return sendGET(defaultHeader(), $data, $newPath);
}

function listGiftCards($limit = 10, $page = 1) {
		$method = "GET";
		$endpoint = endpointWith("/api/v1/giftcards", $method);
		global $verbose;
		checkAuth();
		
		$data = array("limit" => $limit,
	"page" => $page
		);
		
		if ($method != "GET") {
			$data = formattedParametersWithData($endpoint, $data);
		}
		
		$newPath = fillEndpointPathWithRequirements($endpoint, $data);
		$data = cleanEndpointRequirementsFromData($endpoint, $data);
		return sendGET(defaultHeader(), $data, $newPath);
}

function getGiftCard($card) {
		$method = "GET";
		$endpoint = endpointWith("/api/v1/giftcards/{card}", $method);
		global $verbose;
		checkAuth();
		
		$data = array("card" => $card
		);
		
		if ($method != "GET") {
			$data = formattedParametersWithData($endpoint, $data);
		}
		
		$newPath = fillEndpointPathWithRequirements($endpoint, $data);
		$data = cleanEndpointRequirementsFromData($endpoint, $data);
		return sendGET(defaultHeader(), $data, $newPath);
}

function listGiftCardImages($card, $limit = 10, $page = 1) {
		$method = "GET";
		$endpoint = endpointWith("/api/v1/giftcards/{card}/cardimages", $method);
		global $verbose;
		checkAuth();
		
		$data = array("card" => $card,
	"limit" => $limit,
	"page" => $page
		);
		
		if ($method != "GET") {
			$data = formattedParametersWithData($endpoint, $data);
		}
		
		$newPath = fillEndpointPathWithRequirements($endpoint, $data);
		$data = cleanEndpointRequirementsFromData($endpoint, $data);
		return sendGET(defaultHeader(), $data, $newPath);
}

function getGiftCardImage($card, $cardImage) {
		$method = "GET";
		$endpoint = endpointWith("/api/v1/giftcards/{card}/cardimages/{cardImage}", $method);
		global $verbose;
		checkAuth();
		
		$data = array("card" => $card,
	"cardImage" => $cardImage
		);
		
		if ($method != "GET") {
			$data = formattedParametersWithData($endpoint, $data);
		}
		
		$newPath = fillEndpointPathWithRequirements($endpoint, $data);
		$data = cleanEndpointRequirementsFromData($endpoint, $data);
		return sendGET(defaultHeader(), $data, $newPath);
}

function getMembers($offset, $city, $state, $email, $firstName, $lastName, $mobileNumber, $phoneNumber, $limit = 5) {
		$method = "GET";
		$endpoint = endpointWith("/api/v1/members", $method);
		global $verbose;
		checkAuth();
		
		$data = array("offset" => $offset,
	"limit" => $limit,
	"city" => $city,
	"state" => $state,
	"email" => $email,
	"firstName" => $firstName,
	"lastName" => $lastName,
	"mobileNumber" => $mobileNumber,
	"phoneNumber" => $phoneNumber
		);
		
		if ($method != "GET") {
			$data = formattedParametersWithData($endpoint, $data);
		}
		
		$newPath = fillEndpointPathWithRequirements($endpoint, $data);
		$data = cleanEndpointRequirementsFromData($endpoint, $data);
		return sendGET(defaultHeader(), $data, $newPath);
}

function createNewMember($member, $firstName, $lastName, $email, $mobileNumber = false, $phoneNumber = false, $streetAddress = false, $streetAddress2 = false, $city = false, $state = false, $country = false, $zipPostalCode = false, $username = false) {
		$method = "POST";
		$endpoint = endpointWith("/api/v1/members", $method);
		global $verbose;
		checkAuth();
		
		$data = array("member" => $member,
	"firstName" => $firstName,
	"lastName" => $lastName,
	"mobileNumber" => $mobileNumber,
	"phoneNumber" => $phoneNumber,
	"streetAddress" => $streetAddress,
	"streetAddress2" => $streetAddress2,
	"city" => $city,
	"state" => $state,
	"country" => $country,
	"zipPostalCode" => $zipPostalCode,
	"email" => $email,
	"username" => $username
		);
		
		if ($method != "GET") {
			$data = formattedParametersWithData($endpoint, $data);
		}
		
		$newPath = fillEndpointPathWithRequirements($endpoint, $data);
		$data = cleanEndpointRequirementsFromData($endpoint, $data);
		return sendPOST(defaultHeader(), $data, $newPath);
}

function deleteMember($member) {
		$method = "DELETE";
		$endpoint = endpointWith("/api/v1/members/{member}", $method);
		global $verbose;
		checkAuth();
		
		$data = array("member" => $member
		);
		
		if ($method != "GET") {
			$data = formattedParametersWithData($endpoint, $data);
		}
		
		$newPath = fillEndpointPathWithRequirements($endpoint, $data);
		$data = cleanEndpointRequirementsFromData($endpoint, $data);
		return sendDELETE(defaultHeader(), $data, $newPath);
}

function getMember($member) {
		$method = "GET";
		$endpoint = endpointWith("/api/v1/members/{member}", $method);
		global $verbose;
		checkAuth();
		
		$data = array("member" => $member
		);
		
		if ($method != "GET") {
			$data = formattedParametersWithData($endpoint, $data);
		}
		
		$newPath = fillEndpointPathWithRequirements($endpoint, $data);
		$data = cleanEndpointRequirementsFromData($endpoint, $data);
		return sendGET(defaultHeader(), $data, $newPath);
}

function updateMember($member, $firstName, $lastName, $mobileNumber = false, $phoneNumber = false, $streetAddress = false, $streetAddress2 = false, $city = false, $state = false, $country = false, $zipPostalCode = false) {
		$method = "PATCH";
		$endpoint = endpointWith("/api/v1/members/{member}", $method);
		global $verbose;
		checkAuth();
		
		$data = array("member" => $member,
	"firstName" => $firstName,
	"lastName" => $lastName,
	"mobileNumber" => $mobileNumber,
	"phoneNumber" => $phoneNumber,
	"streetAddress" => $streetAddress,
	"streetAddress2" => $streetAddress2,
	"city" => $city,
	"state" => $state,
	"country" => $country,
	"zipPostalCode" => $zipPostalCode
		);
		
		if ($method != "GET") {
			$data = formattedParametersWithData($endpoint, $data);
		}
		
		$newPath = fillEndpointPathWithRequirements($endpoint, $data);
		$data = cleanEndpointRequirementsFromData($endpoint, $data);
		return sendPATCH(defaultHeader(), $data, $newPath);
}

function updateOrCreateNewMember($member, $firstName = false, $lastName = false, $mobileNumber = false, $phoneNumber = false, $streetAddress = false, $streetAddress2 = false, $city = false, $state = false, $country = false, $zipPostalCode = false) {
		$method = "PUT";
		$endpoint = endpointWith("/api/v1/members/{member}", $method);
		global $verbose;
		checkAuth();
		
		$data = array("member" => $member,
	"firstName" => $firstName,
	"lastName" => $lastName,
	"mobileNumber" => $mobileNumber,
	"phoneNumber" => $phoneNumber,
	"streetAddress" => $streetAddress,
	"streetAddress2" => $streetAddress2,
	"city" => $city,
	"state" => $state,
	"country" => $country,
	"zipPostalCode" => $zipPostalCode
		);
		
		if ($method != "GET") {
			$data = formattedParametersWithData($endpoint, $data);
		}
		
		$newPath = fillEndpointPathWithRequirements($endpoint, $data);
		$data = cleanEndpointRequirementsFromData($endpoint, $data);
		return sendPUT(defaultHeader(), $data, $newPath);
}

function convertDollarsToPiPoints($member, $token, $cardNumber, $month, $year, $security, $source, $amount) {
		$method = "POST";
		$endpoint = endpointWith("/api/v1/members/{member}/credittransactions", $method);
		global $verbose;
		checkAuth();
		
		$data = array("member" => $member,
	"token" => $token,
	"cardNumber" => $cardNumber,
	"month" => $month,
	"year" => $year,
	"security" => $security,
	"source" => $source,
	"amount" => $amount
		);
		
		if ($method != "GET") {
			$data = formattedParametersWithData($endpoint, $data);
		}
		
		$newPath = fillEndpointPathWithRequirements($endpoint, $data);
		$data = cleanEndpointRequirementsFromData($endpoint, $data);
		return sendPOST(defaultHeader(), $data, $newPath);
}

function addGiftCardToMember($member, $brand, $productId) {
		$method = "POST";
		$endpoint = endpointWith("/api/v1/members/{member}/giftcards", $method);
		global $verbose;
		checkAuth();
		
		$data = array("member" => $member,
	"brand" => $brand,
	"productId" => $productId
		);
		
		if ($method != "GET") {
			$data = formattedParametersWithData($endpoint, $data);
		}
		
		$newPath = fillEndpointPathWithRequirements($endpoint, $data);
		$data = cleanEndpointRequirementsFromData($endpoint, $data);
		return sendPOST(defaultHeader(), $data, $newPath);
}

function requestPasswordReset($member) {
		$method = "GET";
		$endpoint = endpointWith("/api/v1/members/{member}/passwords", $method);
		global $verbose;
		checkAuth();
		
		$data = array("member" => $member
		);
		
		if ($method != "GET") {
			$data = formattedParametersWithData($endpoint, $data);
		}
		
		$newPath = fillEndpointPathWithRequirements($endpoint, $data);
		$data = cleanEndpointRequirementsFromData($endpoint, $data);
		return sendGET(defaultHeader(), $data, $newPath);
}

function getMemberPoints($member) {
		$method = "GET";
		$endpoint = endpointWith("/api/v1/members/{member}/points", $method);
		global $verbose;
		checkAuth();
		
		$data = array("member" => $member
		);
		
		if ($method != "GET") {
			$data = formattedParametersWithData($endpoint, $data);
		}
		
		$newPath = fillEndpointPathWithRequirements($endpoint, $data);
		$data = cleanEndpointRequirementsFromData($endpoint, $data);
		return sendGET(defaultHeader(), $data, $newPath);
}

function getOutstandingReferrals($member, $filter, $offset = 1, $limit = 5) {
		$method = "GET";
		$endpoint = endpointWith("/api/v1/members/{member}/referrals", $method);
		global $verbose;
		checkAuth();
		
		$data = array("member" => $member,
	"offset" => $offset,
	"limit" => $limit,
	"filter" => $filter
		);
		
		if ($method != "GET") {
			$data = formattedParametersWithData($endpoint, $data);
		}
		
		$newPath = fillEndpointPathWithRequirements($endpoint, $data);
		$data = cleanEndpointRequirementsFromData($endpoint, $data);
		return sendGET(defaultHeader(), $data, $newPath);
}

function createReferralForMember($member, $referral, $memberReferral = false, $pointTransferReferral = false) {
		$method = "POST";
		$endpoint = endpointWith("/api/v1/members/{member}/referrals", $method);
		global $verbose;
		checkAuth();
		
		$data = array("member" => $member,
	"memberReferral" => $memberReferral,
	"referral" => $referral,
	"pointTransferReferral" => $pointTransferReferral
		);
		
		if ($method != "GET") {
			$data = formattedParametersWithData($endpoint, $data);
		}
		
		$newPath = fillEndpointPathWithRequirements($endpoint, $data);
		$data = cleanEndpointRequirementsFromData($endpoint, $data);
		return sendPOST(defaultHeader(), $data, $newPath);
}

function getReferralForMember($member, $referral) {
		$method = "GET";
		$endpoint = endpointWith("/api/v1/members/{member}/referrals/{referral}", $method);
		global $verbose;
		checkAuth();
		
		$data = array("member" => $member,
	"referral" => $referral
		);
		
		if ($method != "GET") {
			$data = formattedParametersWithData($endpoint, $data);
		}
		
		$newPath = fillEndpointPathWithRequirements($endpoint, $data);
		$data = cleanEndpointRequirementsFromData($endpoint, $data);
		return sendGET(defaultHeader(), $data, $newPath);
}

function getReferredMembers($member, $limit = 10, $page = 1) {
		$method = "GET";
		$endpoint = endpointWith("/api/v1/members/{member}/referreds", $method);
		global $verbose;
		checkAuth();
		
		$data = array("member" => $member,
	"limit" => $limit,
	"page" => $page
		);
		
		if ($method != "GET") {
			$data = formattedParametersWithData($endpoint, $data);
		}
		
		$newPath = fillEndpointPathWithRequirements($endpoint, $data);
		$data = cleanEndpointRequirementsFromData($endpoint, $data);
		return sendGET(defaultHeader(), $data, $newPath);
}

function getReferredMemberByReferral($member, $referral) {
		$method = "GET";
		$endpoint = endpointWith("/api/v1/members/{member}/referreds/{referral}", $method);
		global $verbose;
		checkAuth();
		
		$data = array("member" => $member,
	"referral" => $referral
		);
		
		if ($method != "GET") {
			$data = formattedParametersWithData($endpoint, $data);
		}
		
		$newPath = fillEndpointPathWithRequirements($endpoint, $data);
		$data = cleanEndpointRequirementsFromData($endpoint, $data);
		return sendGET(defaultHeader(), $data, $newPath);
}

function activateMember($member, $token, $password, $confirm) {
		$method = "PATCH";
		$endpoint = endpointWith("/api/v1/members/{member}/registrations/{token}", $method);
		global $verbose;
		checkAuth();
		
		$data = array("member" => $member,
	"token" => $token,
	"password" => $password,
	"confirm" => $confirm
		);
		
		if ($method != "GET") {
			$data = formattedParametersWithData($endpoint, $data);
		}
		
		$newPath = fillEndpointPathWithRequirements($endpoint, $data);
		$data = cleanEndpointRequirementsFromData($endpoint, $data);
		return sendPATCH(defaultHeader(), $data, $newPath);
}

function getPointTransactions($member) {
		$method = "GET";
		$endpoint = endpointWith("/api/v1/members/{member}/transactions", $method);
		global $verbose;
		checkAuth();
		
		$data = array("member" => $member
		);
		
		if ($method != "GET") {
			$data = formattedParametersWithData($endpoint, $data);
		}
		
		$newPath = fillEndpointPathWithRequirements($endpoint, $data);
		$data = cleanEndpointRequirementsFromData($endpoint, $data);
		return sendGET(defaultHeader(), $data, $newPath);
}

function getPointTransactionForMember($member, $transaction) {
		$method = "GET";
		$endpoint = endpointWith("/api/v1/members/{member}/transactions/{transaction}", $method);
		global $verbose;
		checkAuth();
		
		$data = array("member" => $member,
	"transaction" => $transaction
		);
		
		if ($method != "GET") {
			$data = formattedParametersWithData($endpoint, $data);
		}
		
		$newPath = fillEndpointPathWithRequirements($endpoint, $data);
		$data = cleanEndpointRequirementsFromData($endpoint, $data);
		return sendGET(defaultHeader(), $data, $newPath);
}

function getMemberWallets($member, $limit = 10, $page = 1) {
		$method = "GET";
		$endpoint = endpointWith("/api/v1/members/{member}/wallets", $method);
		global $verbose;
		checkAuth();
		
		$data = array("member" => $member,
	"limit" => $limit,
	"page" => $page
		);
		
		if ($method != "GET") {
			$data = formattedParametersWithData($endpoint, $data);
		}
		
		$newPath = fillEndpointPathWithRequirements($endpoint, $data);
		$data = cleanEndpointRequirementsFromData($endpoint, $data);
		return sendGET(defaultHeader(), $data, $newPath);
}

function deleteMemberWallet($member, $wallet) {
		$method = "DELETE";
		$endpoint = endpointWith("/api/v1/members/{member}/wallets/{wallet}", $method);
		global $verbose;
		checkAuth();
		
		$data = array("member" => $member,
	"wallet" => $wallet
		);
		
		if ($method != "GET") {
			$data = formattedParametersWithData($endpoint, $data);
		}
		
		$newPath = fillEndpointPathWithRequirements($endpoint, $data);
		$data = cleanEndpointRequirementsFromData($endpoint, $data);
		return sendDELETE(defaultHeader(), $data, $newPath);
}

function getMemberWallet($member, $wallet) {
		$method = "GET";
		$endpoint = endpointWith("/api/v1/members/{member}/wallets/{wallet}", $method);
		global $verbose;
		checkAuth();
		
		$data = array("member" => $member,
	"wallet" => $wallet
		);
		
		if ($method != "GET") {
			$data = formattedParametersWithData($endpoint, $data);
		}
		
		$newPath = fillEndpointPathWithRequirements($endpoint, $data);
		$data = cleanEndpointRequirementsFromData($endpoint, $data);
		return sendGET(defaultHeader(), $data, $newPath);
}

function getAllGiftCardsInWallet($member, $wallet) {
		$method = "GET";
		$endpoint = endpointWith("/api/v1/members/{member}/wallets/{wallet}/giftcards", $method);
		global $verbose;
		checkAuth();
		
		$data = array("member" => $member,
	"wallet" => $wallet
		);
		
		if ($method != "GET") {
			$data = formattedParametersWithData($endpoint, $data);
		}
		
		$newPath = fillEndpointPathWithRequirements($endpoint, $data);
		$data = cleanEndpointRequirementsFromData($endpoint, $data);
		return sendGET(defaultHeader(), $data, $newPath);
}

function getGiftCardInWallet($member, $wallet, $card) {
		$method = "GET";
		$endpoint = endpointWith("/api/v1/members/{member}/wallets/{wallet}/giftcards/{card}", $method);
		global $verbose;
		checkAuth();
		
		$data = array("member" => $member,
	"wallet" => $wallet,
	"card" => $card
		);
		
		if ($method != "GET") {
			$data = formattedParametersWithData($endpoint, $data);
		}
		
		$newPath = fillEndpointPathWithRequirements($endpoint, $data);
		$data = cleanEndpointRequirementsFromData($endpoint, $data);
		return sendGET(defaultHeader(), $data, $newPath);
}

function getCurrentCardBalance($member, $wallet, $card) {
		$method = "GET";
		$endpoint = endpointWith("/api/v1/members/{member}/wallets/{wallet}/giftcards/{card}/currentbalance", $method);
		global $verbose;
		checkAuth();
		
		$data = array("member" => $member,
	"wallet" => $wallet,
	"card" => $card
		);
		
		if ($method != "GET") {
			$data = formattedParametersWithData($endpoint, $data);
		}
		
		$newPath = fillEndpointPathWithRequirements($endpoint, $data);
		$data = cleanEndpointRequirementsFromData($endpoint, $data);
		return sendGET(defaultHeader(), $data, $newPath);
}

function resetPasswordWithToken($token, $plainPassword, $first, $second) {
		$method = "POST";
		$endpoint = endpointWith("/api/v1/passwordresets/{token}", $method);
		global $verbose;
		checkAuth();
		
		$data = array("token" => $token,
	"plainPassword" => $plainPassword,
	"first" => $first,
	"second" => $second
		);
		
		if ($method != "GET") {
			$data = formattedParametersWithData($endpoint, $data);
		}
		
		$newPath = fillEndpointPathWithRequirements($endpoint, $data);
		$data = cleanEndpointRequirementsFromData($endpoint, $data);
		return sendPOST(defaultHeader(), $data, $newPath);
}

function redeemReferral($invitation, $referral, $email, $username, $firstName, $lastName, $plainPassword, $first, $second) {
		$method = "POST";
		$endpoint = endpointWith("/api/v1/referrals", $method);
		global $verbose;
		checkAuth();
		
		$data = array("invitation" => $invitation,
	"referral" => $referral,
	"email" => $email,
	"username" => $username,
	"firstName" => $firstName,
	"lastName" => $lastName,
	"plainPassword" => $plainPassword,
	"first" => $first,
	"second" => $second
		);
		
		if ($method != "GET") {
			$data = formattedParametersWithData($endpoint, $data);
		}
		
		$newPath = fillEndpointPathWithRequirements($endpoint, $data);
		$data = cleanEndpointRequirementsFromData($endpoint, $data);
		return sendPOST(defaultHeader(), $data, $newPath);
}

function getToken($client_secret, $client_id, $grant_type, $username = false, $password = false, $refresh_token = false, $response_type = false, $code = false, $redirect_uri = false) {
		$method = "GET";
		$endpoint = endpointWith("/api/v1/token", $method);
		global $verbose;
		checkAuth();
		
		$data = array("client_secret" => $client_secret,
	"client_id" => $client_id,
	"grant_type" => $grant_type,
	"username" => $username,
	"password" => $password,
	"refresh_token" => $refresh_token,
	"response_type" => $response_type,
	"code" => $code,
	"redirect_uri" => $redirect_uri
		);
		
		if ($method != "GET") {
			$data = formattedParametersWithData($endpoint, $data);
		}
		
		$newPath = fillEndpointPathWithRequirements($endpoint, $data);
		$data = cleanEndpointRequirementsFromData($endpoint, $data);
		return sendGET(defaultHeader(), $data, $newPath);
}

function loginUser($client_secret, $client_id, $grant_type, $username = false, $password = false, $refresh_token = false, $response_type = false, $code = false, $redirect_uri = false) {
		$method = "POST";
		$endpoint = endpointWith("/api/v1/token", $method);
		global $verbose;
		checkAuth();
		
		$data = array("client_secret" => $client_secret,
	"client_id" => $client_id,
	"grant_type" => $grant_type,
	"username" => $username,
	"password" => $password,
	"refresh_token" => $refresh_token,
	"response_type" => $response_type,
	"code" => $code,
	"redirect_uri" => $redirect_uri
		);
		
		if ($method != "GET") {
			$data = formattedParametersWithData($endpoint, $data);
		}
		
		$newPath = fillEndpointPathWithRequirements($endpoint, $data);
		$data = cleanEndpointRequirementsFromData($endpoint, $data);
		return sendPOST(defaultHeader(), $data, $newPath);
}

?>