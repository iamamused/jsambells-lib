
# libjsambells

This repo is a collection of libraries and scripts that make mobile app development easier.

There's nothing proprietary in here, just a collection of tools in a common place.

## dependencies

- php 5.5
- (others to list)

## installation:

install homebrew if not installed: <http://brew.sh> then run:

	brew tap iamamused/tap
	brew install libjsambells
	mkdir ~/.libjsambells
	
### You'll also need these to successfully run some of the tools.
	
	sudo gem install babelish

# Tools

## bin/googlesheet2localizablestrings

This is a simple wrapper for babelish that adds a bit of extra niceness to the output.

It assumes that the google drive file is in the format:

* Column 1 is the key
* Column 2 is the comment
* Remaining columns are either ignored or used as a language. To be used as a language, the column header must be a valid language code in proper letter case. 

for example:

| Keys         | Formats                                  | Description  | Max Size     | en                              | fr                              |
| Instructions | Instructions                             | Instructions | Instructions | Instructions                    | Instructions                    |
| ------------ |----------------------------------------- | ------------ | ------------ | ------------------------------- | ------------------------------- |
| my_key       | [product]=%@\|%1$s;[value]=%.02\|%1$.02f | product cost | 40           | Product [product] costs [value] | Produit [product] coûte [value] |

Column headers with valid language codes will be auto identified. 

**For ios, if the Base.lproj exists, the first language will used for the Localizable.strings in the Base.lproj**
**For android, the first language will be used as the strings.xml in the values folder.**
	
### Setup

1. You'll need to generate a Google Drive API credentials file (service type) and place it in:
		
		~/.libjsambells/googlesheet2localizablestrings.credentials.json
	
2. Next make a new Google Sheet with the above format and place it in the Google Drive accessable by the user you created.
3. Copy any of the existing strings into the sheet (you can use `babelish` installed earlier [to convert your existing string/xml into CSV format](https://github.com/netbe/Babelish/wiki/How-to-Use).)
4. Copy the ID portion of the Google Sheet url for configuration in the next step.

Now configure your project as outlined below when and when you need a new string, update the google sheet then build you project and it will appear as necessary.

#### Configure for iOS

In xcode add a run script build phase to the target (drage it to the top of the list)

For the script enter:

	if [ "$CONFIGURATION" == "Debug" ]; then
		echo "Generating localization files"
		googlesheet2localizablestrings ios "${SRCROOT}/${PROJECT_NAME}" "google-id-copied-above"
	fi

#### Configure for android

In Android Studio, add the following Gradle task in your app.gradle file:

	android {
		task googlesheet2localizablestrings(type: Exec, description: 'googlesheet2localizablestrings') {
			println "Generating localization files"
			environment 'PATH', '$PATH:/usr/bin/:/usr/local/bin'
			commandLine '/usr/local/bin/googlesheet2localizablestrings', 'android', 'src/main/res', 'google-id-copied-above'
		}
		preBuild.dependsOn googlesheet2localizablestrings
	}

# build and enjoy!

