<?php

namespace App\Helpers;

use App\Models\Master\Organization;
use Carbon\Carbon;
use Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Helpers
{
  public static function appClasses()
  {

    $data = config('custom.custom');


    // default data array
    $DefaultData = [
      'myLayout' => 'vertical',
      'myTheme' => 'theme-default',
      'myStyle' => 'light',
      'myRTLSupport' => true,
      'myRTLMode' => true,
      'hasCustomizer' => true,
      'showDropdownOnHover' => true,
      'displayCustomizer' => true,
      'contentLayout' => 'compact',
      'headerType' => 'fixed',
      'navbarType' => 'fixed',
      'menuFixed' => true,
      'menuCollapsed' => false,
      'footerFixed' => false,
      'menuFlipped' => false,
      // 'menuOffcanvas' => false,
      'customizerControls' => [
        'rtl',
        'style',
        'headerType',
        'contentLayout',
        'layoutCollapsed',
        'showDropdownOnHover',
        'layoutNavbarOptions',
        'themes',
      ],
        'defaultLanguage'=>'en',
    ];

    // if any key missing of array from custom.php file it will be merge and set a default value from dataDefault array and store in data variable
    $data = array_merge($DefaultData, $data);

    // All options available in the template
    $allOptions = [
      'myLayout' => ['vertical', 'horizontal', 'blank', 'front'],
      'menuCollapsed' => [true, false],
      'hasCustomizer' => [true, false],
      'showDropdownOnHover' => [true, false],
      'displayCustomizer' => [true, false],
      'contentLayout' => ['compact', 'wide'],
      'headerType' => ['fixed', 'static'],
      'navbarType' => ['fixed', 'static', 'hidden'],
      'myStyle' => ['light', 'dark', 'system'],
      'myTheme' => ['theme-default', 'theme-bordered', 'theme-semi-dark'],
      'myRTLSupport' => [true, false],
      'myRTLMode' => [true, false],
      'menuFixed' => [true, false],
      'footerFixed' => [true, false],
      'menuFlipped' => [true, false],
      // 'menuOffcanvas' => [true, false],
      'customizerControls' => [],
      // 'defaultLanguage'=>array('en'=>'en','fr'=>'fr','de'=>'de','ar'=>'ar'),
    ];

    //if myLayout value empty or not match with default options in custom.php config file then set a default value
    foreach ($allOptions as $key => $value) {
      if (array_key_exists($key, $DefaultData)) {
        if (gettype($DefaultData[$key]) === gettype($data[$key])) {
          // data key should be string
          if (is_string($data[$key])) {
            // data key should not be empty
            if (isset($data[$key]) && $data[$key] !== null) {
              // data key should not be exist inside allOptions array's sub array
              if (!array_key_exists($data[$key], $value)) {
                // ensure that passed value should be match with any of allOptions array value
                $result = array_search($data[$key], $value, 'strict');
                if (empty($result) && $result !== 0) {
                  $data[$key] = $DefaultData[$key];
                }
              }
            } else {
              // if data key not set or
              $data[$key] = $DefaultData[$key];
            }
          }
        } else {
          $data[$key] = $DefaultData[$key];
        }
      }
    }
    $styleVal = $data['myStyle'] == "dark" ? "dark" : "light";
    if (isset($_COOKIE['style'])) {
      $styleVal = $_COOKIE['style'];
    }
    //layout classes
    $layoutClasses = [
      'layout' => $data['myLayout'],
      'theme' => $data['myTheme'],
      'style' => $styleVal,
      'styleOpt' => $data['myStyle'],
      'rtlSupport' => $data['myRTLSupport'],
      'rtlMode' => $data['myRTLMode'],
      'textDirection' => $data['myRTLMode'],
      'menuCollapsed' => $data['menuCollapsed'],
      'hasCustomizer' => $data['hasCustomizer'],
      'showDropdownOnHover' => $data['showDropdownOnHover'],
      'displayCustomizer' => $data['displayCustomizer'],
      'contentLayout' => $data['contentLayout'],
      'headerType' => $data['headerType'],
      'navbarType' => $data['navbarType'],
      'menuFixed' => $data['menuFixed'],
      'footerFixed' => $data['footerFixed'],
      'menuFlipped' => $data['menuFlipped'],
      'customizerControls' => $data['customizerControls'],
    ];

    // sidebar Collapsed
    if ($layoutClasses['menuCollapsed'] == true) {
      $layoutClasses['menuCollapsed'] = 'layout-menu-collapsed';
    }

    // Header Type
    if ($layoutClasses['headerType'] == 'fixed') {
      $layoutClasses['headerType'] = 'layout-menu-fixed';
    }
    // Navbar Type
    if ($layoutClasses['navbarType'] == 'fixed') {
      $layoutClasses['navbarType'] = 'layout-navbar-fixed';
    } elseif ($layoutClasses['navbarType'] == 'static') {
      $layoutClasses['navbarType'] = '';
    } else {
      $layoutClasses['navbarType'] = 'layout-navbar-hidden';
    }

    // Menu Fixed
    if ($layoutClasses['menuFixed'] == true) {
      $layoutClasses['menuFixed'] = 'layout-menu-fixed';
    }


    // Footer Fixed
    if ($layoutClasses['footerFixed'] == true) {
      $layoutClasses['footerFixed'] = 'layout-footer-fixed';
    }

    // Menu Flipped
    if ($layoutClasses['menuFlipped'] == true) {
      $layoutClasses['menuFlipped'] = 'layout-menu-flipped';
    }

    // Menu Offcanvas
    // if ($layoutClasses['menuOffcanvas'] == true) {
    //   $layoutClasses['menuOffcanvas'] = 'layout-menu-offcanvas';
    // }

    // RTL Supported template
    if ($layoutClasses['rtlSupport'] == true) {
      $layoutClasses['rtlSupport'] = '/rtl';
    }

    // RTL Layout/Mode
    if ($layoutClasses['rtlMode'] == true) {
      $layoutClasses['rtlMode'] = 'rtl';
      $layoutClasses['textDirection'] = 'rtl';
    } else {
      $layoutClasses['rtlMode'] = 'ltr';
      $layoutClasses['textDirection'] = 'ltr';
    }

    // Show DropdownOnHover for Horizontal Menu
    if ($layoutClasses['showDropdownOnHover'] == true) {
      $layoutClasses['showDropdownOnHover'] = true;
    } else {
      $layoutClasses['showDropdownOnHover'] = false;
    }

    // To hide/show display customizer UI, not js
    if ($layoutClasses['displayCustomizer'] == true) {
      $layoutClasses['displayCustomizer'] = true;
    } else {
      $layoutClasses['displayCustomizer'] = false;
    }

    return $layoutClasses;
  }

  public static function updatePageConfig($pageConfigs)
  {
    $demo = 'custom';
    if (isset($pageConfigs)) {
      if (count($pageConfigs) > 0) {
        foreach ($pageConfigs as $config => $val) {
          Config::set('custom.' . $demo . '.' . $config, $val);
        }
      }
    }
  }

  public static function generateTxNumber($jenis_surat = null)
  {
        $currentYear = Carbon::now()->translatedFormat('Y');

        if (!is_null($jenis_surat)) {
          if ($jenis_surat == 'masuk') {
            $totalData = DB::table('surat_masuk')->select('*')->whereYear('created_at', $currentYear)->count();
            $number = '0000';
          } else {
            $totalData = DB::table('surat_keluar')->select('*')->whereYear('created_at', $currentYear)->count();
            $number = '0000';
          }
        } else {
          $totalData = DB::table('surat_masuk')->select('*')->whereYear('created_at', $currentYear)->count();
          $number = '0000';
        }


        if($totalData != null){
            $lastId = explode('-', $totalData->tx_number);
            $nextId = (int)$lastId[count($lastId)-1] + 1;
            $nextId = sprintf("%04d", $nextId);
        } else {
            $nextId = '0001';
        }

        if (!is_null($jenis_surat)) {
          if ($jenis_surat == 'masuk') {
            $transNumber = "TX-SM-$currentYear-$nextId";
          } else {
            $transNumber = "TX-SK-$currentYear-$nextId";
          }
        } else {
          $transNumber = "TX-SM-$currentYear-$nextId";
        }



        return $transNumber;
  }

  public static function generateNoAgenda($org = 'DIVPROPAM',$jenis_surat=null)
  {
        $currentYear = Carbon::now()->translatedFormat('Y');
        $currentMonth = (int)Carbon::now()->translatedFormat('m');
        $currentMonth = self::numberToRomanRepresentation($currentMonth);

        if (!is_null($jenis_surat)) {
          if ($jenis_surat == 'masuk') {
            $totalData = DB::table('surat_masuk')->select('*')->whereYear('created_at', $currentYear)->orderBy('tx_number', 'DESC')->first();
          } else {
            $totalData = DB::table('surat_keluar')->select('*')->whereYear('created_at', $currentYear)->orderBy('tx_number', 'DESC')->first();
          }
        } else {
          $totalData = DB::table('surat_masuk')->select('*')->whereYear('created_at', $currentYear)->orderBy('tx_number', 'DESC')->first();
        }

        if($totalData != null){
            $lastId = explode('-', $totalData->tx_number);
            $nextId = (int)$lastId[count($lastId)-1] + 1;
            $nextId = sprintf("%04d", $nextId);
        } else {
            $nextId = '0001';
        }

        $noAgenda = "$nextId/$currentMonth/$currentYear/$org";

        return $noAgenda;
  }

  public static function numberToRomanRepresentation($number) {
    $map = array('X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
    $returnValue = '';
    while ($number > 0) {
        foreach ($map as $roman => $int) {
            if($number >= $int) {
                $number -= $int;
                $returnValue .= $roman;
                break;
            }
        }
    }
    return $returnValue;
}

  public static function generateRandomString($length = 10)
  {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
  }

  public static function getAllParentOrg($org)
  {
    $orgs = [];
    $child = clone ($org);
    $parent = Organization::where('id',$child->parent_id)->first();

    if (!is_null($parent)) {
      while (!is_null($child->parent_id)) {
        array_unshift($orgs,$parent);
        // array_push($orgs,$parent);
  
        $child = $parent;
        $parent = Organization::where('id',$child->parent_id)->first();
      }
    }

    return $orgs;
  }

  public static function getAllChildOrg($org,$current_org)
  {
    $orgs = [];

    $orgs = $org->where('parent_id',$current_org->id);

    return $orgs;
  }

  public static function getBulanRomawi($bulan)
  {
    switch ($bulan) {
        case 1:
            return "I";
            break;
        case 2:
            return "II";
            break;
        case 3:
            return "III";
            break;
        case 4:
            return "IV";
            break;
        case 5:
            return "V";
            break;
        case 6:
            return "VI";
            break;
        case 7:
            return "VII";
            break;
        case 8:
            return "VIII";
            break;
        case 9:
            return "IX";
            break;
        case 10:
            return "X";
            break;
        case 11:
            return "XI";
            break;
        case 12:
            return "XII";
            break;
    }
  }
}
