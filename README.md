# PBX_g33k's Info Library Skeleton#

This is the core of PBX_g33k's various libraries which aggregate/search data from various sources.
This library is intended as a base and should not be used directly.

## Projects build on this library ##
* [PHPMusicInfo](https://github.com/PBXg33k/php-music-info)
* PHPJAVInfo (unreleased as of yet)

## Requirements ##
Libraries build upon this library have the following minimal requirements.
Please note that additional requirements might be met.

In order to use this library your environment MUST meet the following criteria:
* PHP 5.6 (or later)
	* curl extension

## Installation ##

### Using Composer (recommended) ###
Add the music-info package to your `composer.json` file.

``` json
{
    "require": {
        "pbxg33k/php-base-info": "dev-master"
    }
}
```

Or via the command line in the root of your project's installation.

``` bash
$ composer require "pbxg33k/php-base-info"
```

This will install the latest stable version.

~~To try the latest features, add `master-dev` as version. But be warned, this branch is unstable and not intended for production use.~~ **During this development stage master-dev will have the latest stable development version untill 1.0.0 has been released**

### Without composer ###
1. Download this repository as a zip file.
2. Extract the content of the zip file to a directory in your application
3. Add files to your project
	* Map `pbxg33k/php-base-info` to this directory if your autoloader is PSR-4 compatible
	* Include `autoloader.php` to your project bootstrap if either you don't have an autoloader or your autoloader is not PSR-4 compatible

## Todo

ADD TODO AND INSTRUCTIONS

## License

Copyright (c) 2016-2017 Oguzhan Uysal
This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see <http://www.gnu.org/licenses/>.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
