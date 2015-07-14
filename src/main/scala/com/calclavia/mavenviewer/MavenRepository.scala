package com.calclavia.mavenviewer

import org.scalajs.dom
import org.scalajs.jquery.jQuery

import scala.scalajs.js._
import scala.scalajs.js.annotation.JSExport
import scalatags.JsDom.all._

/**
 * A Maven Repository definition
 * @param publicRoot - The root URL of the Maven repository used for download URLs. E.g: http://example.com/maven
 * @author Calclavia
 */
@JSExport
class MavenRepository(val publicRoot: String) {
	repo =>

	var propertyInterpreter = Map.empty[String, String]

	/**
	 * @param group - The group of the Maven config package format. E.g: com.example.project
	 * @param name - The artifiact ID of the Maven project.
	 */
	class Project(val group: String, val name: String, val extension: String = "jar") {
		proj =>
		val dir = publicRoot + "/" + group.replaceAll("\\.", "/") + "/" + name + "/"

		/**
		 * A sequence of versions from oldest to newest.
		 */
		var versions = Seq.empty[Version]

		/**
		 * Classifiers we're interested in searching.
		 */
		var classifiers = Set.empty[String]

		var pagination = 10

		/**
		 * Generates the data for this project.
		 */
		def generate(callback: () => {}) {
			val mavenXMLUrl = dir + "maven-metadata.xml"

			val request = eval("encodeURIComponent(\"select * from xml where url='" + mavenXMLUrl + "'\")")
			val yql = "http://query.yahooapis.com/v1/public/yql?q=" + request + "&format=xml&callback=?"
			jQuery.getJSON(yql, null, (data: Dynamic) => {
				val resultArr = data.results.asInstanceOf[Array[_]]
				val xmlResult = resultArr(0).asInstanceOf[String]
				val xml = jQuery(jQuery.parseXML(xmlResult))

				println(xmlResult)
				xml.find("versions")
					.children()
					.each({
					(d: Any, self: dom.Element) => {
						versions :+= new Version(jQuery(self).text.trim)
						null
					}
				})

				callback()
			})
		}

		def html = table(
			th(
				td("Version"),
				td("Artifacts")
			),
			for (v <- versions)
				yield v.html
		)

		class Version(val number: String) {
			//Artifacts
			var artifacts = Seq.empty
			//Dependencies
			var dependencies = Seq.empty
			//Changelog
			var changes = Seq.empty

			val dir = proj.dir + number + "/"

			var artifactNames: Seq[String] = proj.classifiers.map(proj.name + "-" + number + "-" + _).toSeq

			def html =
				tr(
					td(
						b(number)
					),
					td(
						ul(
							for {
								artifactName <- artifactNames
								artifactFile = artifactName + "." + proj.extension
								artifactPath = dir + artifactFile
							}
								yield
								li(
									a(
										href := artifactPath,
										p(artifactName)
									)
								)
						)
					)
				)
		}

	}

}
