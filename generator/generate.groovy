/* groovylint-disable NoDef */
/*
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements.  See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership.  The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License.  You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied.  See the License for the
 * specific language governing permissions and limitations
 * under the License.
 */

import org.apache.tinkerpop.gremlin.process.computer.traversal.step.map.ConnectedComponent
import org.apache.tinkerpop.gremlin.process.computer.traversal.step.map.PageRank
import org.apache.tinkerpop.gremlin.process.computer.traversal.step.map.PeerPressure
import org.apache.tinkerpop.gremlin.process.computer.traversal.step.map.ShortestPath
import org.apache.tinkerpop.gremlin.process.traversal.TraversalSource
import org.apache.tinkerpop.gremlin.process.traversal.dsl.graph.GraphTraversal
import org.apache.tinkerpop.gremlin.process.traversal.dsl.graph.GraphTraversalSource
import org.apache.tinkerpop.gremlin.process.traversal.P
import org.apache.tinkerpop.gremlin.process.traversal.TextP
import org.apache.tinkerpop.gremlin.process.traversal.IO
import org.apache.tinkerpop.gremlin.process.traversal.dsl.graph.__
import org.apache.tinkerpop.gremlin.process.traversal.step.util.WithOptions
import java.lang.reflect.Modifier
import java.lang.reflect.TypeVariable
import java.lang.reflect.GenericArrayType
import sun.reflect.generics.reflectiveObjects.ParameterizedTypeImpl
import groovy.json.JsonOutput

def typeMap = [
    'Long': 'long',
    'Double': 'double',
    'Integer': 'int',
    'String': 'string',
    'boolean': 'bool',
    'Object': 'object',
    'String[]': 'string[]',
    'Object[]': 'object[]',
    'Class': 'Type',
    'Class[]': 'Type[]',
    'java.util.Map<java.lang.String, java.lang.Object>': 'Map<string, object>',
    'java.util.Map<java.lang.String, E2>': 'Map<string, E2>',
    'java.util.Map<java.lang.String, B>': 'Map<string, E2>',
    'java.util.Map<java.lang.Object, E2>': 'Map<object, E2>',
    'java.util.Map<java.lang.Object, B>': 'Map<object, E2>',
    'java.util.List<E>': 'List<E>',
    'java.util.List<A>': 'List<E2>',
    'java.util.Map<K, V>': 'Map<K, V>',
    'java.util.Collection<E2>': 'Collection<E2>',
    'java.util.Collection<B>': 'Collection<E2>',
    'java.util.Map<K, java.lang.Long>': 'Map<K, long>',
    'TraversalMetrics': 'object',
    'VertexProgram': 'object',
    'E2': 'mixed',
]

def toGenericType = { name ->
    String typeName = typeMap.getOrDefault(name, name)
    if (typeName.equals(name) && (typeName.contains('? extends') || typeName.equals('Tree'))) {
        typeName = 'object'
    }
    return typeName
}

def getJavaParameterTypeNames = { method ->
    return method.parameters.
            collect { param ->
                param.type.simpleName
            }
}

def getJavaParamTypeString = { method ->
    getJavaParameterTypeNames(method).join(',')
}

def getParamTypeString = { method ->
    return method.parameters.
            collect { param ->
                toGenericType(param.type.simpleName)
            }.join(',')
}

def getParams = { method, useGenericParams ->
    def parameters = method.parameters
    if (parameters.length == 0) {
        return []
    }

    def genericTypes = method.getGenericParameterTypes()
    def resultParameters = parameters.
            toList().indexed().
            collect { index, param ->
                ["name": param.name, "type": toGenericType(param.type.simpleName), "variadic": false]
            }.
            toArray()

    if (method.isVarArgs()) {
        def lastIndex = resultParameters.length - 1
        resultParameters[lastIndex].variadic = true;
    }

    resultParameters
}

def getMethodDescription = { method ->
    ['methodName': method.name, 'parameters': getParams(method, true)]
}

def methods = [
    'predicates': P.getMethods().
        findAll { Modifier.isStatic(it.getModifiers()) }.
        findAll { P.isAssignableFrom(it.returnType) }.
        collect { it.name }.
        unique().
        sort { a, b -> a <=> b },

    'textPredicates': TextP.getMethods().
            findAll { Modifier.isStatic(it.getModifiers()) }.
            findAll { TextP.isAssignableFrom(it.returnType) }.
            collect { it.name }.
            unique().
            sort { a, b -> a <=> b },
    'sourceStepMethods': GraphTraversalSource.getMethods(). // SOURCE STEPS
            findAll { GraphTraversalSource.equals(it.returnType) }.
            findAll {
                !it.name.equals('clone') &&
                        !it.name.equals(TraversalSource.Symbols.withRemote) &&
                        !it.name.equals(TraversalSource.Symbols.withComputer)
            }.
            sort { a, b -> a.name <=> b.name ?: getJavaParamTypeString(a) <=> getJavaParamTypeString(b) }.
            unique { a, b -> a.name <=> b.name ?: getParamTypeString(a) <=> getParamTypeString(b) }.
            collect { javaMethod ->
                getMethodDescription(javaMethod)
            },
    'sourceSpawnMethods': GraphTraversalSource.getMethods(). // SPAWN STEPS
            findAll { GraphTraversal.equals(it.returnType) }.
            sort { a, b -> a.name <=> b.name ?: getJavaParamTypeString(a) <=> getJavaParamTypeString(b) }.
            unique { a, b -> a.name <=> b.name ?: getParamTypeString(a) <=> getParamTypeString(b) }.
            collect { javaMethod ->
                getMethodDescription(javaMethod)
            },
    'graphStepMethods': GraphTraversal.getMethods().
            findAll { GraphTraversal.equals(it.returnType) }.
            findAll { !it.name.equals('clone') && !it.name.equals('iterate') }.
            sort { a, b -> a.name <=> b.name ?: getJavaParamTypeString(a) <=> getJavaParamTypeString(b) }.
            unique { a, b -> a.name <=> b.name ?: getParamTypeString(a) <=> getParamTypeString(b) }.
            collect { javaMethod ->
                getMethodDescription(javaMethod)
            },
    'anonStepMethods': __.class.getMethods().
            findAll { GraphTraversal.equals(it.returnType) }.
            findAll { Modifier.isStatic(it.getModifiers()) }.
            findAll { !it.name.equals('__') && !it.name.equals('start') }.
            sort { a, b -> a.name <=> b.name ?: getJavaParamTypeString(a) <=> getJavaParamTypeString(b) }.
            unique { a, b -> a.name <=> b.name ?: getParamTypeString(a) <=> getParamTypeString(b) }.
            collect { javaMethod ->
                getMethodDescription(javaMethod)
            },
]

def json = JsonOutput.toJson(methods)
def jsonFile = new File("${projectBaseDir}/methods.json")
jsonFile.write(JsonOutput.prettyPrint(json))
