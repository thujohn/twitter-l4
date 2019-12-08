<?php

namespace Thujohn\Twitter\Traits;

use BadMethodCallException;

trait AccountActivityTrait
{
	/**
	 * Creates HMAC SHA-256 hash from incomming crc_token and consumer secret.
	 * This base64 encoded hash needs to be returned by the application when Twitter calls the webhook.
	 *
	 * @param  mixed $crcToken
	 *
	 * @return void
	 */
	public function CrcHash($crcToken)
	{
		$hash = hash_hmac('sha256', $crcToken, $this->tconfig['CONSUMER_SECRET'], true);

		return 'sha256='.base64_encode($hash);
	}
	
	/**
	 * Registers a webhook $url for all event types.
	 *
	 * @param  mixed $env
	 * @param  mixed $url
	 *
	 * @return void
	 */
	public function setWebhook($env, $url)
	{
		return $this->post("account_activity/all/{$env}/webhooks", ['url' => $url]);
	}

	/**
	 * Returns webhook URLs for the given environment (or all, if none provided) and their statuses for the authenticating app. 
	 *
	 * @param  mixed $env
	 *
	 * @return void
	 */
	public function getWebhooks($env = null)
	{
		return $this->get("account_activity/all/".($env ? $env."/" : ""). "webhooks");
	}

	/**
	 * Triggers the challenge response check (CRC) for the given enviroments webhook for all activites.
	 * If the check is successful, returns 204 and reenables the webhook by setting its status to valid.
	 *
	 * @param  mixed $env
	 * @param  mixed $webhookId
	 *
	 * @return void
	 */
	public function updateWebhooks($env, $webhookId)
	{
		return $this->query("account_activity/all/{$env}/webhooks/{$webhookId}", "PUT");
	}

	/**
	 * Removes the webhook from the provided application's all activities configuration.
	 * The webhook ID can be accessed by making a call to GET /1.1/account_activity/all/webhooks (getWebhooks).
	 *
	 * @param  mixed $env
	 * @param  mixed $webhookId
	 *
	 * @return void
	 */
	public function destroyWebhook($env, $webhookId)
	{
		return $this->delete("account_activity/all/{$env}/webhooks/{$webhookId}");
	}

	/**
	 * Subscribes the provided application to all events for the provided environment for all message types. 
	 * After activation, all events for the requesting user will be sent to the application’s webhook via POST request.
	 *
	 * @param  mixed $env
	 *
	 * @return void
	 */
	public function setSubscriptions($env)
	{
		return $this->post("account_activity/all/{$env}/subscriptions");
	}

	/**
	 * Provides a way to determine if a webhook configuration is subscribed to the provided user’s events.
	 * If the provided user context has an active subscription with provided application, returns 204 OK.
	 * If the response code is not 204, then the user does not have an active subscription.
	 * See HTTP Response code and error messages below for details.
	 *
	 * @param  mixed $env
	 *
	 * @return void
	 */
	public function getSubscriptions($env)
	{
		return $this->get("account_activity/all/{$env}/subscriptions");
	}

	/**
	 * NOT WORKING AT THE MOMENT; NEEDS APPLICATION-ONLY BEARER TOKEN
	 * Returns the count of subscriptions that are currently active on your account for all activities.
	 *
	 * @return void
	 */
	public function getSubscriptionsCount()
	{
		// return $this->get("account_activity/all/subscriptions/count");
	}

	/**
	 * NOT WORKING AT THE MOMENT; NEEDS APPLICATION-ONLY BEARER TOKEN
	 * Returns a list of the current All Activity type subscriptions.
	 *
	 * @param  mixed $env
	 *
	 * @return void
	 */
	public function getSubscriptionsList($env)
	{
		// return $this->get("account_activity/all/{$env}/subscriptions/list");
	}

	/**
	 * NOT WORKING AT THE MOMENT; NEEDS APPLICATION-ONLY BEARER TOKEN
	 * Deactivates subscription for the specified user id from the environment.
	 * After deactivation, all events for the requesting user will no longer be sent to the webhook URL.
	 *
	 * @param  mixed $env
	 * @param  mixed $userId
	 *
	 * @return void
	 */
	public function destroyUserSubscriptions($env, $userId)
	{
		// return $this->delete("account_activity/all/{$env}/subscriptions/{$userId}");
	}

}
