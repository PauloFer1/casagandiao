<?php

/**
 * tarambola CMS - Content Management Simplified. <http://www.madebytarambola.com>
 * Copyright (C) 2008 Philippe Archambault <philippe.archambault@gmail.com>
 * Copyright (C) 2008 Martijn van der Kleijn <martijn.niji@gmail.com>
 *
 * This file is part of tarambola CMS.
 *
 * tarambola CMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * tarambola CMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with tarambola CMS.  If not, see <http://www.gnu.org/licenses/>.
 *
 * tarambola CMS has made an exception to the GNU General Public License for plugins.
 * See exception.txt for details and the full text.
 */


function explode_uri($uri)
{
    return preg_split('/\//', $uri, -1, PREG_SPLIT_NO_EMPTY);
}

function find_page_by_uri($uri)
{
    global $__tarambola_CONN__;
    
    $uri = trim($uri, '/');
    
    $has_behavior = false;
    
    // adding the home root
    $urls = array_merge(array(''), explode_uri($uri));
    $url = '';
 
    $page = new stdClass;
    $page->id = 0;
    
    $parent = false;
    
    foreach ($urls as $page_slug)
    {
        $url = ltrim($url . '/' . $page_slug, '/');
        
        if ($page = find_page_by_slug($page_slug, $parent))
        {
            // check for behavior
            if ($page->behavior_id != '')
            {
                // add a instance of the behavior with the name of the behavior 
                $params = explode_uri(substr($uri, strlen($url)));
                $page->{$page->behavior_id} = Behavior::load($page->behavior_id, $page, $params);
                
                return $page;
            }
        }
        else
        {
            break;
        }
        
        $parent = $page;
        
    } // foreach
    
    return ( ! $page && $has_behavior) ? $parent: $page;
} // find_page_by_slug

function find_page_by_slug($slug, &$parent)
{
    global $__tarambola_CONN__;
    
    $page_class = 'Page';
    
    $parent_id = $parent ? $parent->id: 0;
    
    $sql = 'SELECT page.*, author.name AS author, updator.name AS updator '
         . 'FROM '.TABLE_PREFIX.'page AS page '
         . 'LEFT JOIN '.TABLE_PREFIX.'user AS author ON author.id = page.created_by_id '
         . 'LEFT JOIN '.TABLE_PREFIX.'user AS updator ON updator.id = page.updated_by_id '
         . 'WHERE slug = ? AND parent_id = ? AND (status_id='.Page::STATUS_REVIEWED.' OR status_id='.Page::STATUS_PUBLISHED.' OR status_id='.Page::STATUS_HIDDEN.')';
    
    $stmt = $__tarambola_CONN__->prepare($sql);
    
    $stmt->execute(array($slug, $parent_id));
    
    if ($page = $stmt->fetchObject())
    {
        // hook to be able to redefine the page class with behavior
        if ( ! empty($parent->behavior_id))
        {
            // will return Page by default (if not found!)
            $page_class = Behavior::loadPageHack($parent->behavior_id);
        }
        
        // create the object page
        $page = new $page_class($page, $parent);
        
        // assign all is parts
        $page->part = get_parts($page->id);
        
        return $page;
    }
    else return false;
}
function find_page_by_id($id)
{
    global $__tarambola_CONN__;

    $page_class = 'Page';
    $parent=false;

    $sql = 'SELECT page.*, author.name AS author, updator.name AS updator '
         . 'FROM '.TABLE_PREFIX.'page AS page '
         . 'LEFT JOIN '.TABLE_PREFIX.'user AS author ON author.id = page.created_by_id '
         . 'LEFT JOIN '.TABLE_PREFIX.'user AS updator ON updator.id = page.updated_by_id '
         . 'WHERE page.id = ? AND (status_id='.Page::STATUS_REVIEWED.' OR status_id='.Page::STATUS_PUBLISHED.' OR status_id='.Page::STATUS_HIDDEN.' OR status_id='.Page::STATUS_DRAFT.')';

    $stmt = $__tarambola_CONN__->prepare($sql);

    $stmt->execute(array($id));

    if ($page = $stmt->fetchObject())
    {
        // hook to be able to redefine the page class with behavior
        if ( ! empty($parent->behavior_id))
        {
            // will return Page by default (if not found!)
            $page_class = Behavior::loadPageHack($parent->behavior_id);
        }

        // create the object page
        $page = new $page_class($page, $parent);

        // assign all is parts
        $page->part = get_parts($page->id);

        return $page;
    }
    else return false;
}
function get_parts($page_id, $cond=null)
{
    if($cond==null)
    {
        global $__tarambola_CONN__;

        $objPart = new stdClass;

        $sql = 'SELECT name, content_html FROM '.TABLE_PREFIX.'page_part WHERE page_id=?';

        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
            $stmt->execute(array($page_id));

            while ($part = $stmt->fetchObject())
                $objPart->{$part->name} = $part;
        }
    }
    else
        {
            global $__tarambola_CONN__;

            $objPart = new stdClass;

            $sql = 'SELECT name, content_html FROM '.TABLE_PREFIX.'page_part WHERE page_id=?'.$cond;

            if ($stmt = $__tarambola_CONN__->prepare($sql))
            {
                $stmt->execute(array($page_id));

                while ($part = $stmt->fetchObject())
                    $objPart->{$part->name} = $part;
           }
        }
    
    return $objPart;
}

function url_match($url)
{
    $url = trim($url, '/');
    
    if (CURRENT_URI == $url)
        return true;
    
    return false;
}
  
function url_start_with($url)
{
    $url = trim($url, '/');
    
    if (CURRENT_URI == $url)
        return true;
    
    if (strpos(CURRENT_URI, $url) === 0)
        return true;
    
    return false;
}

?>