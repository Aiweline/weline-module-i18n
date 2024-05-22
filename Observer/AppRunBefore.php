<?php

namespace Weline\I18n\Observer;

use Weline\Framework\App\Env;
use Weline\Framework\Event\Event;
use Weline\Framework\Event\ObserverInterface;
use Weline\Framework\Http\Cookie;
use Weline\Framework\Manager\ObjectManager;
use Weline\I18n\Model\Locals;

class AppRunBefore implements ObserverInterface
{
    /**
     * @inheritDoc
     */
    public function execute(Event $event)
    {
        if(CLI){
            return;
        }
        $_SERVER['WELINE-USER-LANG'] = Cookie::get('WELINE-USER-LANG');
        # 处理第一级语言代码
        $uri = ltrim($_SERVER['REQUEST_URI'], '/');
        if ($uri) {
            $uri_arr = explode('/', $uri);
            if (!$uri_arr) {
                return;
            }
            # 排除后端地址和接口隐私地址
            $replace_part = '';
            $backend_pre = Env::getInstance()->getConfig('admin');
            if ($backend_pre == $uri_arr[0]) {
                array_shift($uri_arr);
                $replace_part = $backend_pre;
            } else {
                $api_admin_pre = Env::getInstance()->getConfig('api_admin');
                if ($api_admin_pre == $uri_arr[0]) {
                    array_shift($uri_arr);
                    $replace_part = $api_admin_pre;
                }
            }
            if (!$uri_arr) {
                return;
            }
            # 如果还有路由
            $lang_path = array_shift($uri_arr);
            # 必须有前两个字符是否都是小写字母,且第三个字符必须是_
            if (strlen($lang_path) > 3 and ctype_lower(substr($lang_path, 0, 2)) and $lang_path[2] === '_') {
                # 如果查询得到属于语言包，则删除此路由
                /**@var Locals $locals */
                $locals = ObjectManager::getInstance(Locals::class);
                $local = $locals->where(Locals::fields_CODE, $lang_path)
                    ->where(Locals::fields_IS_INSTALL, 1)
                    ->where(Locals::fields_IS_ACTIVE, 1)
                    ->find()
                    ->fetch();
                if ($local) {
                    $_SERVER['REQUEST_URI'] = $replace_part . '/' . implode('/', $uri_arr);
                    $_SERVER['ORIGIN_REQUEST_URI'] = $uri;
                    Cookie::set('WELINE-USER-LANG', $lang_path, 3600 * 24 * 30);
                    $_SERVER['WELINE-USER-LANG'] = $lang_path;
                }
            }
        }
    }
}