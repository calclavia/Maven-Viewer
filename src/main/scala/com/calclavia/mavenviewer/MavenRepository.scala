package com.calclavia.mavenviewer

import org.scalajs.dom.ext.Ajax

import scala.scalajs.concurrent.JSExecutionContext.Implicits.runNow
import scala.scalajs.js.annotation.JSExport
import scalatags.JsDom.all._

/**
 * A Maven Repository definition
 * @param publicRoot - The root URL of the Maven repository used for download URLs. E.g: http://example.com/maven
 * @param privateRoot - The root URL of the Maven repository used for Ajax access. E.g: /var/www/maven
 * @param group - The group of the Maven config package format. E.g: com.example.project
 * @param name - The artifiact ID of the Maven project.
 * @author Calclavia
 */
@JSExport
class MavenRepository(val publicRoot: String, var privateRoot : String , val group: String, val name: String) {
	repo =>

	val accessDir = privateRoot + "/" + group.replaceAll("\\.", "/") + "/" + name + "/"

	var propertyInterpreter = Map.empty[String, String]

	var versions = Seq.empty[Version]

	/**
	 * Renders the builds.
	 * @param last The amount of builds to show.
	 */
	def renderBuilds(last: Int = 10): String = {
		generate()
		return ""
	}

	/**
	 * Generates the data for this repository.
	 */
	def generate() {
		val mavenXMLUrl = accessDir + "maven-metadata.xml"

		Ajax.get(mavenXMLUrl).onSuccess {
			case xhr =>
				println(xhr.responseText)
		}
	}

	class Version(dir: String, url: String) {
		//Version number
		var number = ""
		//Artifacts
		var artifacts = Seq.empty
		//Dependencies
		var dependencies = Seq.empty
		//Changelog
		var changes = Seq.empty

		val directory = repo.publicRoot

		/**
		 * Generates the data for this version.
		 */
		def generate() {

		}
	}

}
