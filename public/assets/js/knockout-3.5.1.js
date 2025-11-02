// Knockout.js 3.5.1のミニマル版を手動で配置してください
// 以下のURLからダウンロード: https://knockoutjs.com/downloads/knockout-3.5.1.js
// または npm install knockout でインストール

// 一時的な簡易版（テスト用）
(function(factory) {
    if (typeof exports === 'object' && typeof module === 'object') {
        module.exports = factory();
    } else if (typeof define === 'function' && define.amd) {
        define([], factory);
    } else {
        window.ko = factory();
    }
})(function() {
    'use strict';
    var ko = {};
    
    // Observable
    ko.observable = function(initialValue) {
        var _value = initialValue;
        var _subscribers = [];
        
        var observable = function() {
            if (arguments.length > 0) {
                _value = arguments[0];
                _subscribers.forEach(function(sub) { sub(_value); });
            }
            return _value;
        };
        
        observable.subscribe = function(callback) {
            _subscribers.push(callback);
        };
        
        return observable;
    };
    
    // ObservableArray
    ko.observableArray = function(initialValue) {
        var _array = initialValue || [];
        var observable = ko.observable(_array);
        
        observable.push = function(item) {
            _array.push(item);
            observable(_array);
            return observable;
        };
        
        observable.remove = function(item) {
            var index = _array.indexOf(item);
            if (index >= 0) {
                _array.splice(index, 1);
                observable(_array);
            }
            return observable;
        };
        
        return observable;
    };
    
    // Computed
    ko.computed = function(evaluator) {
        return ko.observable(evaluator());
    };
    
    // ApplyBindings
    ko.applyBindings = function(viewModel, rootNode) {
        rootNode = rootNode || document.body;
        
        // data-bind="value: property"
        var valueElements = rootNode.querySelectorAll('[data-bind*="value:"]');
        valueElements.forEach(function(el) {
            var binding = el.getAttribute('data-bind');
            var match = binding.match(/value:\s*(\w+)/);
            if (match) {
                var prop = match[1];
                if (viewModel[prop]) {
                    el.value = viewModel[prop]();
                    el.addEventListener('input', function() {
                        viewModel[prop](el.value);
                    });
                }
            }
        });
        
        // data-bind="text: property"
        var textElements = rootNode.querySelectorAll('[data-bind*="text:"]');
        textElements.forEach(function(el) {
            var binding = el.getAttribute('data-bind');
            var match = binding.match(/text:\s*([\w\.()]+)/);
            if (match) {
                var expr = match[1];
                if (expr.includes('()')) {
                    var prop = expr.replace('()', '');
                    if (viewModel[prop]) {
                        var value = viewModel[prop]();
                        el.textContent = typeof value === 'function' ? value() : value;
                    }
                } else {
                    if (viewModel[expr]) {
                        el.textContent = viewModel[expr]();
                    }
                }
            }
        });
        
        // data-bind="click: method"
        var clickElements = rootNode.querySelectorAll('[data-bind*="click:"]');
        clickElements.forEach(function(el) {
            var binding = el.getAttribute('data-bind');
            var match = binding.match(/click:\s*(\$parent\.)?(\w+)/);
            if (match) {
                var method = match[2];
                el.addEventListener('click', function() {
                    if (viewModel[method]) {
                        var data = el.closest('[data-bind*="foreach:"]') ? el.textContent.trim() : viewModel;
                        viewModel[method](data);
                    }
                });
            }
        });
        
        // data-bind="foreach: array"
        var foreachElements = rootNode.querySelectorAll('[data-bind*="foreach:"]');
        foreachElements.forEach(function(container) {
            var binding = container.getAttribute('data-bind');
            var match = binding.match(/foreach:\s*(\w+)/);
            if (match) {
                var prop = match[1];
                if (viewModel[prop]) {
                    var template = container.innerHTML;
                    var renderList = function() {
                        var items = viewModel[prop]();
                        container.innerHTML = '';
                        items.forEach(function(item) {
                            var li = document.createElement('li');
                            li.innerHTML = template;
                            var textSpan = li.querySelector('[data-bind*="text: $data"]');
                            if (textSpan) {
                                textSpan.textContent = item;
                            }
                            var removeBtn = li.querySelector('[data-bind*="click: $parent.removeItem"]');
                            if (removeBtn) {
                                removeBtn.addEventListener('click', function() {
                                    viewModel.removeItem(item);
                                });
                            }
                            container.appendChild(li);
                        });
                    };
                    renderList();
                    viewModel[prop].subscribe(renderList);
                }
            }
        });
    };
    
    return ko;
});
