package com.calclavia.mavenviewer

import org.scalajs.dom
import org.scalajs.jquery.jQuery

import scala.collection.mutable
import scala.scalajs.js
import scala.scalajs.js._
import scala.scalajs.js.annotation.JSExport
import scalatags.JsDom.all._

object MavenRepository extends js.JSApp {
	override def main() {

	}
}

/**
 * A Maven Repository definition
 * @param publicRoot - The root URL of the Maven repository used for download URLs. E.g: http://example.com/maven
 * @author Calclavia
 */
@JSExport("MavenRepository")
class MavenRepository(val publicRoot: String) {
	repo =>

	var propertyInterpreter = Map.empty[String, String]

	@JSExport
	def newProject(group: String, name: String, extension: String = "jar") = new Project(group, name, extension)

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
		var classifiers = scala.Array.empty[String]

		@JSExport
		var pagination = 10


		@JSExport
		def reverse() {
			versions = versions.reverse
		}

		@JSExport
		def setClassifiers(arr: js.Array[String]){
			classifiers = arr.toArray
		}

		/**
		 * Generates the data for this project.
		 */
		@JSExport
		def generate(callback: js.Function) {
			val mavenXMLUrl = dir + "maven-metadata.xml"

			val request = eval("encodeURIComponent(\"select * from xml where url='" + mavenXMLUrl + "'\")")
			val yql = "http://query.yahooapis.com/v1/public/yql?q=" + request + "&format=xml&callback=?"
			jQuery.getJSON(yql, null, (data: Dynamic) => {
				val resultArr = data.results.asInstanceOf[Array[_]]
				val xmlResult = resultArr(0).asInstanceOf[String]
				val xml = jQuery(jQuery.parseXML(xmlResult))

				xml.find("versions")
					.children()
					.each({
					(d: Any, self: dom.Element) => {
						versions :+= new Version(jQuery(self).text.trim)
						null
					}
				})

				callback.call(callback)
			})
		}

		@JSExport
		def html = table(
			tr(
				th("Version"),
				th("Files")
			),
			for (v <- versions)
				yield v.html
		)

		@JSExport
		def render = html.render

		class Version(val number: String) {
			//Artifacts
			@JSExport
			var artifacts: Seq[String] = proj.classifiers.map(cl => {
				if(cl.isEmpty())
					proj.name + "-" + number
				else
					proj.name + "-" + number + "-" + cl
			}).toSeq

			//Dependencies
			var dependencies = Seq.empty
			//Changelog
			var changes = Seq.empty

			val dir = proj.dir + number + "/"

			@JSExport
			def html =
				tr(
					td(
						b(number)
					),
					td(
						ul(
							for {
								artifactName <- artifacts
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
