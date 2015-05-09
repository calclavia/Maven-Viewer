# Example application written in Scala.js
Maven Viewer is a Scala-JS application that generates a table for all the builds on a Maven repository for download.

Maven Viewer can run on WordPress.

Requires:
[Scala.js](https://www.scala-js.org/).

## Get started

To get started, open `sbt` in this example project, and execute the task
`fastOptJS`. This creates the file `target/scala-2.11/example-fastopt.js`.
You can now open `index-fastopt.html` in your favorite Web browser!

During development, it is useful to use `~fastOptJS` in sbt, so that each
time you save a source file, a compilation of the project is triggered.
Hence only a refresh of your Web page is needed to see the effects of your
changes.

## License
This software is distributed under the Lesser General Public License (LGPL) version 3. The license can be found here: http://www.gnu.org/copyleft/lesser.html

## The fully optimized version
For ultimate code size reduction, use `fullOptJS`. This will take several
seconds to execute, so typically you only use this for the final, production
version of your application. While `index-fastopt.html` refers to the
JavaScript emitted by `fastOptJS`, `index.html` refers to the optimized
JavaScript emitted by `fullOptJS`.
