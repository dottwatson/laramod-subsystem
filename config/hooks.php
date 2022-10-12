<?php
/**
 * Here is possible to set actions to perform on hooks of prestashop.
 * 
 * Because Laravel and Prestashop needs to dialogate only via web calls,
 * we can only automate this process to make it more flexible and easy to use.
 * 
 * All the data that the hook passes natively, will be json encoded and sent 
 * as post request with body
 * 
 * 
 * to create a new Action on prestashop hook, you need to create a class under App\Hook\HookName
 * 
 * @todo create command for hooks creation
 * 
 * example
 * 
 * namespace App\Hook;
 * 
 * class actionCustomerAccountAdd extends PsActionHook{
 *      
 *      public function handle()
 *      {
 *      
 *      }
 * }
 */


return [
    // 'actionCustomerAccountAdd' => [App\Hooks\Test::class]
    // 'actionCustomerAccountUpdate' => [App\Hooks\Test::class]
    // 'hookDisplayProductActions' => [App\Hooks\Test::class]


    'hookDisplayCustomerAccount' => [App\Hooks\UserAccountReader::class]
];