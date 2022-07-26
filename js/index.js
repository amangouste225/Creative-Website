!(function () {
  'use strict'
  function t(t) {
    if (void 0 === t)
      throw new ReferenceError(
        "this hasn't been initialised - super() hasn't been called"
      )
    return t
  }
  function e(t, e) {
    ;(t.prototype = Object.create(e.prototype)),
      (t.prototype.constructor = t),
      (t.__proto__ = e)
  }
  /*!
   * GSAP 3.6.0
   * https://greensock.com
   *
   * @license Copyright 2008-2021, GreenSock. All rights reserved.
   * Subject to the terms at https://greensock.com/standard-license or for
   * Club GreenSock members, the agreement issued with that membership.
   * @author: Jack Doyle, jack@greensock.com
   */ var r,
    n,
    i,
    o,
    s,
    a,
    u,
    l,
    c,
    d,
    p,
    f,
    h,
    g = {
      autoSleep: 120,
      force3D: 'auto',
      nullTargetWarn: 1,
      units: { lineHeight: '' },
    },
    m = { duration: 0.5, overwrite: !1, delay: 0 },
    y = 1e8,
    _ = 1e-8,
    v = 2 * Math.PI,
    w = v / 4,
    b = 0,
    x = Math.sqrt,
    T = Math.cos,
    S = Math.sin,
    A = function (t) {
      return 'string' == typeof t
    },
    k = function (t) {
      return 'function' == typeof t
    },
    O = function (t) {
      return 'number' == typeof t
    },
    E = function (t) {
      return void 0 === t
    },
    q = function (t) {
      return 'object' == typeof t
    },
    C = function (t) {
      return !1 !== t
    },
    L = function () {
      return 'undefined' != typeof window
    },
    M = function (t) {
      return k(t) || A(t)
    },
    P =
      ('function' == typeof ArrayBuffer && ArrayBuffer.isView) ||
      function () {},
    R = Array.isArray,
    D = /(?:-?\.?\d|\.)+/gi,
    W = /[-+=.]*\d+[.e\-+]*\d*[e\-+]*\d*/g,
    B = /[-+=.]*\d+[.e-]*\d*[a-z%]*/g,
    F = /[-+=.]*\d+\.?\d*(?:e-|e\+)?\d*/gi,
    I = /[+-]=-?[.\d]+/,
    z = /[#\-+.]*\b[a-z\d-=+%.]+/gi,
    H = /[\d.+\-=]+(?:e[-+]\d*)*/i,
    N = {},
    Y = {},
    V = function (t) {
      return (Y = mt(t, N)) && er
    },
    U = function (t, e) {
      return console.warn(
        'Invalid property',
        t,
        'set to',
        e,
        'Missing plugin? gsap.registerPlugin()'
      )
    },
    X = function (t, e) {
      return !e && console.warn(t)
    },
    j = function (t, e) {
      return (t && (N[t] = e) && Y && (Y[t] = e)) || N
    },
    G = function () {
      return 0
    },
    Z = {},
    Q = [],
    $ = {},
    J = {},
    K = {},
    tt = 30,
    et = [],
    rt = '',
    nt = function (t) {
      var e,
        r,
        n = t[0]
      if ((q(n) || k(n) || (t = [t]), !(e = (n._gsap || {}).harness))) {
        for (r = et.length; r-- && !et[r].targetTest(n); );
        e = et[r]
      }
      for (r = t.length; r--; )
        (t[r] && (t[r]._gsap || (t[r]._gsap = new ke(t[r], e)))) ||
          t.splice(r, 1)
      return t
    },
    it = function (t) {
      return t._gsap || nt(Ut(t))[0]._gsap
    },
    ot = function (t, e, r) {
      return (r = t[e]) && k(r)
        ? t[e]()
        : (E(r) && t.getAttribute && t.getAttribute(e)) || r
    },
    st = function (t, e) {
      return (t = t.split(',')).forEach(e) || t
    },
    at = function (t) {
      return Math.round(1e5 * t) / 1e5 || 0
    },
    ut = function (t, e) {
      for (var r = e.length, n = 0; t.indexOf(e[n]) < 0 && ++n < r; );
      return n < r
    },
    lt = function (t, e, r) {
      var n,
        i = O(t[1]),
        o = (i ? 2 : 1) + (e < 2 ? 0 : 1),
        s = t[o]
      if ((i && (s.duration = t[1]), (s.parent = r), e)) {
        for (n = s; r && !('immediateRender' in n); )
          (n = r.vars.defaults || {}), (r = C(r.vars.inherit) && r.parent)
        ;(s.immediateRender = C(n.immediateRender)),
          e < 2 ? (s.runBackwards = 1) : (s.startAt = t[o - 1])
      }
      return s
    },
    ct = function () {
      var t,
        e,
        r = Q.length,
        n = Q.slice(0)
      for ($ = {}, Q.length = 0, t = 0; t < r; t++)
        (e = n[t]) &&
          e._lazy &&
          (e.render(e._lazy[0], e._lazy[1], !0)._lazy = 0)
    },
    dt = function (t, e, r, n) {
      Q.length && ct(), t.render(e, r, n), Q.length && ct()
    },
    pt = function (t) {
      var e = parseFloat(t)
      return (e || 0 === e) && (t + '').match(z).length < 2
        ? e
        : A(t)
        ? t.trim()
        : t
    },
    ft = function (t) {
      return t
    },
    ht = function (t, e) {
      for (var r in e) r in t || (t[r] = e[r])
      return t
    },
    gt = function (t, e) {
      for (var r in e)
        r in t || 'duration' === r || 'ease' === r || (t[r] = e[r])
    },
    mt = function (t, e) {
      for (var r in e) t[r] = e[r]
      return t
    },
    yt = function t(e, r) {
      for (var n in r)
        '__proto__' !== n &&
          'constructor' !== n &&
          'prototype' !== n &&
          (e[n] = q(r[n]) ? t(e[n] || (e[n] = {}), r[n]) : r[n])
      return e
    },
    _t = function (t, e) {
      var r,
        n = {}
      for (r in t) r in e || (n[r] = t[r])
      return n
    },
    vt = function (t) {
      var e = t.parent || n,
        r = t.keyframes ? gt : ht
      if (C(t.inherit))
        for (; e; ) r(t, e.vars.defaults), (e = e.parent || e._dp)
      return t
    },
    wt = function (t, e, r, n) {
      void 0 === r && (r = '_first'), void 0 === n && (n = '_last')
      var i = e._prev,
        o = e._next
      i ? (i._next = o) : t[r] === e && (t[r] = o),
        o ? (o._prev = i) : t[n] === e && (t[n] = i),
        (e._next = e._prev = e.parent = null)
    },
    bt = function (t, e) {
      t.parent && (!e || t.parent.autoRemoveChildren) && t.parent.remove(t),
        (t._act = 0)
    },
    xt = function (t, e) {
      if (t && (!e || e._end > t._dur || e._start < 0))
        for (var r = t; r; ) (r._dirty = 1), (r = r.parent)
      return t
    },
    Tt = function (t) {
      for (var e = t.parent; e && e.parent; )
        (e._dirty = 1), e.totalDuration(), (e = e.parent)
      return t
    },
    St = function t(e) {
      return !e || (e._ts && t(e.parent))
    },
    At = function (t) {
      return t._repeat ? kt(t._tTime, (t = t.duration() + t._rDelay)) * t : 0
    },
    kt = function (t, e) {
      var r = Math.floor((t /= e))
      return t && r === t ? r - 1 : r
    },
    Ot = function (t, e) {
      return (
        (t - e._start) * e._ts +
        (e._ts >= 0 ? 0 : e._dirty ? e.totalDuration() : e._tDur)
      )
    },
    Et = function (t) {
      return (t._end = at(
        t._start + (t._tDur / Math.abs(t._ts || t._rts || _) || 0)
      ))
    },
    qt = function (t, e) {
      var r = t._dp
      return (
        r &&
          r.smoothChildTiming &&
          t._ts &&
          ((t._start = at(
            r._time -
              (t._ts > 0
                ? e / t._ts
                : ((t._dirty ? t.totalDuration() : t._tDur) - e) / -t._ts)
          )),
          Et(t),
          r._dirty || xt(r, t)),
        t
      )
    },
    Ct = function (t, e) {
      var r
      if (
        ((e._time || (e._initted && !e._dur)) &&
          ((r = Ot(t.rawTime(), e)),
          (!e._dur || zt(0, e.totalDuration(), r) - e._tTime > _) &&
            e.render(r, !0)),
        xt(t, e)._dp && t._initted && t._time >= t._dur && t._ts)
      ) {
        if (t._dur < t.duration())
          for (r = t; r._dp; )
            r.rawTime() >= 0 && r.totalTime(r._tTime), (r = r._dp)
        t._zTime = -1e-8
      }
    },
    Lt = function (t, e, r, n) {
      return (
        e.parent && bt(e),
        (e._start = at(r + e._delay)),
        (e._end = at(
          e._start + (e.totalDuration() / Math.abs(e.timeScale()) || 0)
        )),
        (function (t, e, r, n, i) {
          void 0 === r && (r = '_first'), void 0 === n && (n = '_last')
          var o,
            s = t[n]
          if (i) for (o = e[i]; s && s[i] > o; ) s = s._prev
          s
            ? ((e._next = s._next), (s._next = e))
            : ((e._next = t[r]), (t[r] = e)),
            e._next ? (e._next._prev = e) : (t[n] = e),
            (e._prev = s),
            (e.parent = e._dp = t)
        })(t, e, '_first', '_last', t._sort ? '_start' : 0),
        (t._recent = e),
        n || Ct(t, e),
        t
      )
    },
    Mt = function (t, e) {
      return (
        (N.ScrollTrigger || U('scrollTrigger', e)) &&
        N.ScrollTrigger.create(e, t)
      )
    },
    Pt = function (t, e, r, n) {
      return (
        Pe(t, e),
        t._initted
          ? !r &&
            t._pt &&
            ((t._dur && !1 !== t.vars.lazy) || (!t._dur && t.vars.lazy)) &&
            u !== fe.frame
            ? (Q.push(t), (t._lazy = [e, n]), 1)
            : void 0
          : 1
      )
    },
    Rt = function t(e) {
      var r = e.parent
      return r && r._ts && r._initted && !r._lock && (r.rawTime() < 0 || t(r))
    },
    Dt = function (t, e, r, n) {
      var i = t._repeat,
        o = at(e) || 0,
        s = t._tTime / t._tDur
      return (
        s && !n && (t._time *= o / t._dur),
        (t._dur = o),
        (t._tDur = i ? (i < 0 ? 1e10 : at(o * (i + 1) + t._rDelay * i)) : o),
        s && !n ? qt(t, (t._tTime = t._tDur * s)) : t.parent && Et(t),
        r || xt(t.parent, t),
        t
      )
    },
    Wt = function (t) {
      return t instanceof Ee ? xt(t) : Dt(t, t._dur)
    },
    Bt = { _start: 0, endTime: G },
    Ft = function t(e, r) {
      var n,
        i,
        o = e.labels,
        s = e._recent || Bt,
        a = e.duration() >= y ? s.endTime(!1) : e._dur
      return A(r) && (isNaN(r) || r in o)
        ? '<' === (n = r.charAt(0)) || '>' === n
          ? ('<' === n ? s._start : s.endTime(s._repeat >= 0)) +
            (parseFloat(r.substr(1)) || 0)
          : (n = r.indexOf('=')) < 0
          ? (r in o || (o[r] = a), o[r])
          : ((i = +(r.charAt(n - 1) + r.substr(n + 1))),
            n > 1 ? t(e, r.substr(0, n - 1)) + i : a + i)
        : null == r
        ? a
        : +r
    },
    It = function (t, e) {
      return t || 0 === t ? e(t) : e
    },
    zt = function (t, e, r) {
      return r < t ? t : r > e ? e : r
    },
    Ht = function (t) {
      if ('string' != typeof t) return ''
      var e = H.exec(t)
      return e ? t.substr(e.index + e[0].length) : ''
    },
    Nt = [].slice,
    Yt = function (t, e) {
      return (
        t &&
        q(t) &&
        'length' in t &&
        ((!e && !t.length) || (t.length - 1 in t && q(t[0]))) &&
        !t.nodeType &&
        t !== i
      )
    },
    Vt = function (t, e, r) {
      return (
        void 0 === r && (r = []),
        t.forEach(function (t) {
          var n
          return (A(t) && !e) || Yt(t, 1)
            ? (n = r).push.apply(n, Ut(t))
            : r.push(t)
        }) || r
      )
    },
    Ut = function (t, e) {
      return !A(t) || e || (!o && he())
        ? R(t)
          ? Vt(t, e)
          : Yt(t)
          ? Nt.call(t, 0)
          : t
          ? [t]
          : []
        : Nt.call(s.querySelectorAll(t), 0)
    },
    Xt = function (t) {
      return t.sort(function () {
        return 0.5 - Math.random()
      })
    },
    jt = function (t) {
      if (k(t)) return t
      var e = q(t) ? t : { each: t },
        r = be(e.ease),
        n = e.from || 0,
        i = parseFloat(e.base) || 0,
        o = {},
        s = n > 0 && n < 1,
        a = isNaN(n) || s,
        u = e.axis,
        l = n,
        c = n
      return (
        A(n)
          ? (l = c = { center: 0.5, edges: 0.5, end: 1 }[n] || 0)
          : !s && a && ((l = n[0]), (c = n[1])),
        function (t, s, d) {
          var p,
            f,
            h,
            g,
            m,
            _,
            v,
            w,
            b,
            T = (d || e).length,
            S = o[T]
          if (!S) {
            if (!(b = 'auto' === e.grid ? 0 : (e.grid || [1, y])[1])) {
              for (
                v = -1e8;
                v < (v = d[b++].getBoundingClientRect().left) && b < T;

              );
              b--
            }
            for (
              S = o[T] = [],
                p = a ? Math.min(b, T) * l - 0.5 : n % b,
                f = a ? (T * c) / b - 0.5 : (n / b) | 0,
                v = 0,
                w = y,
                _ = 0;
              _ < T;
              _++
            )
              (h = (_ % b) - p),
                (g = f - ((_ / b) | 0)),
                (S[_] = m = u ? Math.abs('y' === u ? g : h) : x(h * h + g * g)),
                m > v && (v = m),
                m < w && (w = m)
            'random' === n && Xt(S),
              (S.max = v - w),
              (S.min = w),
              (S.v = T =
                (parseFloat(e.amount) ||
                  parseFloat(e.each) *
                    (b > T
                      ? T - 1
                      : u
                      ? 'y' === u
                        ? T / b
                        : b
                      : Math.max(b, T / b)) ||
                  0) * ('edges' === n ? -1 : 1)),
              (S.b = T < 0 ? i - T : i),
              (S.u = Ht(e.amount || e.each) || 0),
              (r = r && T < 0 ? ve(r) : r)
          }
          return (
            (T = (S[t] - S.min) / S.max || 0),
            at(S.b + (r ? r(T) : T) * S.v) + S.u
          )
        }
      )
    },
    Gt = function (t) {
      var e = t < 1 ? Math.pow(10, (t + '').length - 2) : 1
      return function (r) {
        var n = Math.round(parseFloat(r) / t) * t * e
        return (n - (n % 1)) / e + (O(r) ? 0 : Ht(r))
      }
    },
    Zt = function (t, e) {
      var r,
        n,
        i = R(t)
      return (
        !i &&
          q(t) &&
          ((r = i = t.radius || y),
          t.values
            ? ((t = Ut(t.values)), (n = !O(t[0])) && (r *= r))
            : (t = Gt(t.increment))),
        It(
          e,
          i
            ? k(t)
              ? function (e) {
                  return (n = t(e)), Math.abs(n - e) <= r ? n : e
                }
              : function (e) {
                  for (
                    var i,
                      o,
                      s = parseFloat(n ? e.x : e),
                      a = parseFloat(n ? e.y : 0),
                      u = y,
                      l = 0,
                      c = t.length;
                    c--;

                  )
                    (i = n
                      ? (i = t[c].x - s) * i + (o = t[c].y - a) * o
                      : Math.abs(t[c] - s)) < u && ((u = i), (l = c))
                  return (
                    (l = !r || u <= r ? t[l] : e),
                    n || l === e || O(e) ? l : l + Ht(e)
                  )
                }
            : Gt(t)
        )
      )
    },
    Qt = function (t, e, r, n) {
      return It(R(t) ? !e : !0 === r ? !!(r = 0) : !n, function () {
        return R(t)
          ? t[~~(Math.random() * t.length)]
          : (r = r || 1e-5) &&
              (n = r < 1 ? Math.pow(10, (r + '').length - 2) : 1) &&
              Math.floor(
                Math.round(
                  (t - r / 2 + Math.random() * (e - t + 0.99 * r)) / r
                ) *
                  r *
                  n
              ) / n
      })
    },
    $t = function (t, e, r) {
      return It(r, function (r) {
        return t[~~e(r)]
      })
    },
    Jt = function (t) {
      for (var e, r, n, i, o = 0, s = ''; ~(e = t.indexOf('random(', o)); )
        (n = t.indexOf(')', e)),
          (i = '[' === t.charAt(e + 7)),
          (r = t.substr(e + 7, n - e - 7).match(i ? z : D)),
          (s +=
            t.substr(o, e - o) +
            Qt(i ? r : +r[0], i ? 0 : +r[1], +r[2] || 1e-5)),
          (o = n + 1)
      return s + t.substr(o, t.length - o)
    },
    Kt = function (t, e, r, n, i) {
      var o = e - t,
        s = n - r
      return It(i, function (e) {
        return r + (((e - t) / o) * s || 0)
      })
    },
    te = function (t, e, r) {
      var n,
        i,
        o,
        s = t.labels,
        a = y
      for (n in s)
        (i = s[n] - e) < 0 == !!r &&
          i &&
          a > (i = Math.abs(i)) &&
          ((o = n), (a = i))
      return o
    },
    ee = function (t, e, r) {
      var n,
        i,
        o = t.vars,
        s = o[e]
      if (s)
        return (
          (n = o[e + 'Params']),
          (i = o.callbackScope || t),
          r && Q.length && ct(),
          n ? s.apply(i, n) : s.call(i)
        )
    },
    re = function (t) {
      return bt(t), t.progress() < 1 && ee(t, 'onInterrupt'), t
    },
    ne = function (t) {
      var e = (t = (!t.name && t.default) || t).name,
        r = k(t),
        n =
          e && !r && t.init
            ? function () {
                this._props = []
              }
            : t,
        i = {
          init: G,
          render: Xe,
          add: Le,
          kill: Ge,
          modifier: je,
          rawVars: 0,
        },
        o = { targetTest: 0, get: 0, getSetter: Ne, aliases: {}, register: 0 }
      if ((he(), t !== n)) {
        if (J[e]) return
        ht(n, ht(_t(t, i), o)),
          mt(n.prototype, mt(i, _t(t, o))),
          (J[(n.prop = e)] = n),
          t.targetTest && (et.push(n), (Z[e] = 1)),
          (e =
            ('css' === e ? 'CSS' : e.charAt(0).toUpperCase() + e.substr(1)) +
            'Plugin')
      }
      j(e, n), t.register && t.register(er, n, $e)
    },
    ie = 255,
    oe = {
      aqua: [0, ie, ie],
      lime: [0, ie, 0],
      silver: [192, 192, 192],
      black: [0, 0, 0],
      maroon: [128, 0, 0],
      teal: [0, 128, 128],
      blue: [0, 0, ie],
      navy: [0, 0, 128],
      white: [ie, ie, ie],
      olive: [128, 128, 0],
      yellow: [ie, ie, 0],
      orange: [ie, 165, 0],
      gray: [128, 128, 128],
      purple: [128, 0, 128],
      green: [0, 128, 0],
      red: [ie, 0, 0],
      pink: [ie, 192, 203],
      cyan: [0, ie, ie],
      transparent: [ie, ie, ie, 0],
    },
    se = function (t, e, r) {
      return (
        ((6 * (t = t < 0 ? t + 1 : t > 1 ? t - 1 : t) < 1
          ? e + (r - e) * t * 6
          : t < 0.5
          ? r
          : 3 * t < 2
          ? e + (r - e) * (2 / 3 - t) * 6
          : e) *
          ie +
          0.5) |
        0
      )
    },
    ae = function (t, e, r) {
      var n,
        i,
        o,
        s,
        a,
        u,
        l,
        c,
        d,
        p,
        f = t ? (O(t) ? [t >> 16, (t >> 8) & ie, t & ie] : 0) : oe.black
      if (!f) {
        if ((',' === t.substr(-1) && (t = t.substr(0, t.length - 1)), oe[t]))
          f = oe[t]
        else if ('#' === t.charAt(0)) {
          if (
            (t.length < 6 &&
              ((n = t.charAt(1)),
              (i = t.charAt(2)),
              (o = t.charAt(3)),
              (t =
                '#' +
                n +
                n +
                i +
                i +
                o +
                o +
                (5 === t.length ? t.charAt(4) + t.charAt(4) : ''))),
            9 === t.length)
          )
            return [
              (f = parseInt(t.substr(1, 6), 16)) >> 16,
              (f >> 8) & ie,
              f & ie,
              parseInt(t.substr(7), 16) / 255,
            ]
          f = [(t = parseInt(t.substr(1), 16)) >> 16, (t >> 8) & ie, t & ie]
        } else if ('hsl' === t.substr(0, 3))
          if (((f = p = t.match(D)), e)) {
            if (~t.indexOf('='))
              return (f = t.match(W)), r && f.length < 4 && (f[3] = 1), f
          } else
            (s = (+f[0] % 360) / 360),
              (a = +f[1] / 100),
              (n =
                2 * (u = +f[2] / 100) -
                (i = u <= 0.5 ? u * (a + 1) : u + a - u * a)),
              f.length > 3 && (f[3] *= 1),
              (f[0] = se(s + 1 / 3, n, i)),
              (f[1] = se(s, n, i)),
              (f[2] = se(s - 1 / 3, n, i))
        else f = t.match(D) || oe.transparent
        f = f.map(Number)
      }
      return (
        e &&
          !p &&
          ((n = f[0] / ie),
          (i = f[1] / ie),
          (o = f[2] / ie),
          (u = ((l = Math.max(n, i, o)) + (c = Math.min(n, i, o))) / 2),
          l === c
            ? (s = a = 0)
            : ((d = l - c),
              (a = u > 0.5 ? d / (2 - l - c) : d / (l + c)),
              (s =
                l === n
                  ? (i - o) / d + (i < o ? 6 : 0)
                  : l === i
                  ? (o - n) / d + 2
                  : (n - i) / d + 4),
              (s *= 60)),
          (f[0] = ~~(s + 0.5)),
          (f[1] = ~~(100 * a + 0.5)),
          (f[2] = ~~(100 * u + 0.5))),
        r && f.length < 4 && (f[3] = 1),
        f
      )
    },
    ue = function (t) {
      var e = [],
        r = [],
        n = -1
      return (
        t.split(ce).forEach(function (t) {
          var i = t.match(B) || []
          e.push.apply(e, i), r.push((n += i.length + 1))
        }),
        (e.c = r),
        e
      )
    },
    le = function (t, e, r) {
      var n,
        i,
        o,
        s,
        a = '',
        u = (t + a).match(ce),
        l = e ? 'hsla(' : 'rgba(',
        c = 0
      if (!u) return t
      if (
        ((u = u.map(function (t) {
          return (
            (t = ae(t, e, 1)) &&
            l +
              (e
                ? t[0] + ',' + t[1] + '%,' + t[2] + '%,' + t[3]
                : t.join(',')) +
              ')'
          )
        })),
        r && ((o = ue(t)), (n = r.c).join(a) !== o.c.join(a)))
      )
        for (s = (i = t.replace(ce, '1').split(B)).length - 1; c < s; c++)
          a +=
            i[c] +
            (~n.indexOf(c)
              ? u.shift() || l + '0,0,0,0)'
              : (o.length ? o : u.length ? u : r).shift())
      if (!i)
        for (s = (i = t.split(ce)).length - 1; c < s; c++) a += i[c] + u[c]
      return a + i[s]
    },
    ce = (function () {
      var t,
        e =
          '(?:\\b(?:(?:rgb|rgba|hsl|hsla)\\(.+?\\))|\\B#(?:[0-9a-f]{3,4}){1,2}\\b'
      for (t in oe) e += '|' + t + '\\b'
      return new RegExp(e + ')', 'gi')
    })(),
    de = /hsl[a]?\(/,
    pe = function (t) {
      var e,
        r = t.join(' ')
      if (((ce.lastIndex = 0), ce.test(r)))
        return (
          (e = de.test(r)),
          (t[1] = le(t[1], e)),
          (t[0] = le(t[0], e, ue(t[1]))),
          !0
        )
    },
    fe = (function () {
      var t,
        e,
        r,
        n,
        u,
        l,
        d = Date.now,
        p = 500,
        f = 33,
        h = d(),
        g = h,
        m = 1e3 / 240,
        y = m,
        _ = [],
        v = function r(i) {
          var o,
            s,
            a,
            c,
            v = d() - g,
            w = !0 === i
          if (
            (v > p && (h += v - f),
            ((o = (a = (g += v) - h) - y) > 0 || w) &&
              ((c = ++n.frame),
              (u = a - 1e3 * n.time),
              (n.time = a /= 1e3),
              (y += o + (o >= m ? 4 : m - o)),
              (s = 1)),
            w || (t = e(r)),
            s)
          )
            for (l = 0; l < _.length; l++) _[l](a, u, c, i)
        }
      return (n = {
        time: 0,
        frame: 0,
        tick: function () {
          v(!0)
        },
        deltaRatio: function (t) {
          return u / (1e3 / (t || 60))
        },
        wake: function () {
          a &&
            (!o &&
              L() &&
              ((i = o = window),
              (s = i.document || {}),
              (N.gsap = er),
              (i.gsapVersions || (i.gsapVersions = [])).push(er.version),
              V(Y || i.GreenSockGlobals || (!i.gsap && i) || {}),
              (r = i.requestAnimationFrame)),
            t && n.sleep(),
            (e =
              r ||
              function (t) {
                return setTimeout(t, (y - 1e3 * n.time + 1) | 0)
              }),
            (c = 1),
            v(2))
        },
        sleep: function () {
          ;(r ? i.cancelAnimationFrame : clearTimeout)(t), (c = 0), (e = G)
        },
        lagSmoothing: function (t, e) {
          ;(p = t || 1e8), (f = Math.min(e, p, 0))
        },
        fps: function (t) {
          ;(m = 1e3 / (t || 240)), (y = 1e3 * n.time + m)
        },
        add: function (t) {
          _.indexOf(t) < 0 && _.push(t), he()
        },
        remove: function (t) {
          var e
          ~(e = _.indexOf(t)) && _.splice(e, 1) && l >= e && l--
        },
        _listeners: _,
      })
    })(),
    he = function () {
      return !c && fe.wake()
    },
    ge = {},
    me = /^[\d.\-M][\d.\-,\s]/,
    ye = /["']/g,
    _e = function (t) {
      for (
        var e,
          r,
          n,
          i = {},
          o = t.substr(1, t.length - 3).split(':'),
          s = o[0],
          a = 1,
          u = o.length;
        a < u;
        a++
      )
        (r = o[a]),
          (e = a !== u - 1 ? r.lastIndexOf(',') : r.length),
          (n = r.substr(0, e)),
          (i[s] = isNaN(n) ? n.replace(ye, '').trim() : +n),
          (s = r.substr(e + 1).trim())
      return i
    },
    ve = function (t) {
      return function (e) {
        return 1 - t(1 - e)
      }
    },
    we = function t(e, r) {
      for (var n, i = e._first; i; )
        i instanceof Ee
          ? t(i, r)
          : !i.vars.yoyoEase ||
            (i._yoyo && i._repeat) ||
            i._yoyo === r ||
            (i.timeline
              ? t(i.timeline, r)
              : ((n = i._ease),
                (i._ease = i._yEase),
                (i._yEase = n),
                (i._yoyo = r))),
          (i = i._next)
    },
    be = function (t, e) {
      return (
        (t &&
          (k(t)
            ? t
            : ge[t] ||
              (function (t) {
                var e,
                  r,
                  n,
                  i,
                  o = (t + '').split('('),
                  s = ge[o[0]]
                return s && o.length > 1 && s.config
                  ? s.config.apply(
                      null,
                      ~t.indexOf('{')
                        ? [_e(o[1])]
                        : ((e = t),
                          (r = e.indexOf('(') + 1),
                          (n = e.indexOf(')')),
                          (i = e.indexOf('(', r)),
                          e.substring(
                            r,
                            ~i && i < n ? e.indexOf(')', n + 1) : n
                          ))
                            .split(',')
                            .map(pt)
                    )
                  : ge._CE && me.test(t)
                  ? ge._CE('', t)
                  : s
              })(t))) ||
        e
      )
    },
    xe = function (t, e, r, n) {
      void 0 === r &&
        (r = function (t) {
          return 1 - e(1 - t)
        }),
        void 0 === n &&
          (n = function (t) {
            return t < 0.5 ? e(2 * t) / 2 : 1 - e(2 * (1 - t)) / 2
          })
      var i,
        o = { easeIn: e, easeOut: r, easeInOut: n }
      return (
        st(t, function (t) {
          for (var e in ((ge[t] = N[t] = o),
          (ge[(i = t.toLowerCase())] = r),
          o))
            ge[
              i + ('easeIn' === e ? '.in' : 'easeOut' === e ? '.out' : '.inOut')
            ] = ge[t + '.' + e] = o[e]
        }),
        o
      )
    },
    Te = function (t) {
      return function (e) {
        return e < 0.5 ? (1 - t(1 - 2 * e)) / 2 : 0.5 + t(2 * (e - 0.5)) / 2
      }
    },
    Se = function t(e, r, n) {
      var i = r >= 1 ? r : 1,
        o = (n || (e ? 0.3 : 0.45)) / (r < 1 ? r : 1),
        s = (o / v) * (Math.asin(1 / i) || 0),
        a = function (t) {
          return 1 === t ? 1 : i * Math.pow(2, -10 * t) * S((t - s) * o) + 1
        },
        u =
          'out' === e
            ? a
            : 'in' === e
            ? function (t) {
                return 1 - a(1 - t)
              }
            : Te(a)
      return (
        (o = v / o),
        (u.config = function (r, n) {
          return t(e, r, n)
        }),
        u
      )
    },
    Ae = function t(e, r) {
      void 0 === r && (r = 1.70158)
      var n = function (t) {
          return t ? --t * t * ((r + 1) * t + r) + 1 : 0
        },
        i =
          'out' === e
            ? n
            : 'in' === e
            ? function (t) {
                return 1 - n(1 - t)
              }
            : Te(n)
      return (
        (i.config = function (r) {
          return t(e, r)
        }),
        i
      )
    }
  st('Linear,Quad,Cubic,Quart,Quint,Strong', function (t, e) {
    var r = e < 5 ? e + 1 : e
    xe(
      t + ',Power' + (r - 1),
      e
        ? function (t) {
            return Math.pow(t, r)
          }
        : function (t) {
            return t
          },
      function (t) {
        return 1 - Math.pow(1 - t, r)
      },
      function (t) {
        return t < 0.5
          ? Math.pow(2 * t, r) / 2
          : 1 - Math.pow(2 * (1 - t), r) / 2
      }
    )
  }),
    (ge.Linear.easeNone = ge.none = ge.Linear.easeIn),
    xe('Elastic', Se('in'), Se('out'), Se()),
    (d = 7.5625),
    (f = 1 / (p = 2.75)),
    xe(
      'Bounce',
      function (t) {
        return 1 - h(1 - t)
      },
      (h = function (t) {
        return t < f
          ? d * t * t
          : t < 0.7272727272727273
          ? d * Math.pow(t - 1.5 / p, 2) + 0.75
          : t < 0.9090909090909092
          ? d * (t -= 2.25 / p) * t + 0.9375
          : d * Math.pow(t - 2.625 / p, 2) + 0.984375
      })
    ),
    xe('Expo', function (t) {
      return t ? Math.pow(2, 10 * (t - 1)) : 0
    }),
    xe('Circ', function (t) {
      return -(x(1 - t * t) - 1)
    }),
    xe('Sine', function (t) {
      return 1 === t ? 1 : 1 - T(t * w)
    }),
    xe('Back', Ae('in'), Ae('out'), Ae()),
    (ge.SteppedEase =
      ge.steps =
      N.SteppedEase =
        {
          config: function (t, e) {
            void 0 === t && (t = 1)
            var r = 1 / t,
              n = t + (e ? 0 : 1),
              i = e ? 1 : 0
            return function (t) {
              return (((n * zt(0, 0.99999999, t)) | 0) + i) * r
            }
          },
        }),
    (m.ease = ge['quad.out']),
    st(
      'onComplete,onUpdate,onStart,onRepeat,onReverseComplete,onInterrupt',
      function (t) {
        return (rt += t + ',' + t + 'Params,')
      }
    )
  var ke = function (t, e) {
      ;(this.id = b++),
        (t._gsap = this),
        (this.target = t),
        (this.harness = e),
        (this.get = e ? e.get : ot),
        (this.set = e ? e.getSetter : Ne)
    },
    Oe = (function () {
      function t(t, e) {
        var r = t.parent || n
        ;(this.vars = t),
          (this._delay = +t.delay || 0),
          (this._repeat = t.repeat === 1 / 0 ? -2 : t.repeat || 0) &&
            ((this._rDelay = t.repeatDelay || 0),
            (this._yoyo = !!t.yoyo || !!t.yoyoEase)),
          (this._ts = 1),
          Dt(this, +t.duration, 1, 1),
          (this.data = t.data),
          c || fe.wake(),
          r && Lt(r, this, e || 0 === e ? e : r._time, 1),
          t.reversed && this.reverse(),
          t.paused && this.paused(!0)
      }
      var e = t.prototype
      return (
        (e.delay = function (t) {
          return t || 0 === t
            ? (this.parent &&
                this.parent.smoothChildTiming &&
                this.startTime(this._start + t - this._delay),
              (this._delay = t),
              this)
            : this._delay
        }),
        (e.duration = function (t) {
          return arguments.length
            ? this.totalDuration(
                this._repeat > 0 ? t + (t + this._rDelay) * this._repeat : t
              )
            : this.totalDuration() && this._dur
        }),
        (e.totalDuration = function (t) {
          return arguments.length
            ? ((this._dirty = 0),
              Dt(
                this,
                this._repeat < 0
                  ? t
                  : (t - this._repeat * this._rDelay) / (this._repeat + 1)
              ))
            : this._tDur
        }),
        (e.totalTime = function (t, e) {
          if ((he(), !arguments.length)) return this._tTime
          var r = this._dp
          if (r && r.smoothChildTiming && this._ts) {
            for (qt(this, t), !r._dp || r.parent || Ct(r, this); r.parent; )
              r.parent._time !==
                r._start +
                  (r._ts >= 0
                    ? r._tTime / r._ts
                    : (r.totalDuration() - r._tTime) / -r._ts) &&
                r.totalTime(r._tTime, !0),
                (r = r.parent)
            !this.parent &&
              this._dp.autoRemoveChildren &&
              ((this._ts > 0 && t < this._tDur) ||
                (this._ts < 0 && t > 0) ||
                (!this._tDur && !t)) &&
              Lt(this._dp, this, this._start - this._delay)
          }
          return (
            (this._tTime !== t ||
              (!this._dur && !e) ||
              (this._initted && Math.abs(this._zTime) === _) ||
              (!t && !this._initted && (this.add || this._ptLookup))) &&
              (this._ts || (this._pTime = t), dt(this, t, e)),
            this
          )
        }),
        (e.time = function (t, e) {
          return arguments.length
            ? this.totalTime(
                Math.min(this.totalDuration(), t + At(this)) % this._dur ||
                  (t ? this._dur : 0),
                e
              )
            : this._time
        }),
        (e.totalProgress = function (t, e) {
          return arguments.length
            ? this.totalTime(this.totalDuration() * t, e)
            : this.totalDuration()
            ? Math.min(1, this._tTime / this._tDur)
            : this.ratio
        }),
        (e.progress = function (t, e) {
          return arguments.length
            ? this.totalTime(
                this.duration() *
                  (!this._yoyo || 1 & this.iteration() ? t : 1 - t) +
                  At(this),
                e
              )
            : this.duration()
            ? Math.min(1, this._time / this._dur)
            : this.ratio
        }),
        (e.iteration = function (t, e) {
          var r = this.duration() + this._rDelay
          return arguments.length
            ? this.totalTime(this._time + (t - 1) * r, e)
            : this._repeat
            ? kt(this._tTime, r) + 1
            : 1
        }),
        (e.timeScale = function (t) {
          if (!arguments.length) return -1e-8 === this._rts ? 0 : this._rts
          if (this._rts === t) return this
          var e =
            this.parent && this._ts ? Ot(this.parent._time, this) : this._tTime
          return (
            (this._rts = +t || 0),
            (this._ts = this._ps || -1e-8 === t ? 0 : this._rts),
            Tt(this.totalTime(zt(-this._delay, this._tDur, e), !0))
          )
        }),
        (e.paused = function (t) {
          return arguments.length
            ? (this._ps !== t &&
                ((this._ps = t),
                t
                  ? ((this._pTime =
                      this._tTime || Math.max(-this._delay, this.rawTime())),
                    (this._ts = this._act = 0))
                  : (he(),
                    (this._ts = this._rts),
                    this.totalTime(
                      this.parent && !this.parent.smoothChildTiming
                        ? this.rawTime()
                        : this._tTime || this._pTime,
                      1 === this.progress() &&
                        (this._tTime -= _) &&
                        Math.abs(this._zTime) !== _
                    ))),
              this)
            : this._ps
        }),
        (e.startTime = function (t) {
          if (arguments.length) {
            this._start = t
            var e = this.parent || this._dp
            return (
              e && (e._sort || !this.parent) && Lt(e, this, t - this._delay),
              this
            )
          }
          return this._start
        }),
        (e.endTime = function (t) {
          return (
            this._start +
            (C(t) ? this.totalDuration() : this.duration()) / Math.abs(this._ts)
          )
        }),
        (e.rawTime = function (t) {
          var e = this.parent || this._dp
          return e
            ? t &&
              (!this._ts ||
                (this._repeat && this._time && this.totalProgress() < 1))
              ? this._tTime % (this._dur + this._rDelay)
              : this._ts
              ? Ot(e.rawTime(t), this)
              : this._tTime
            : this._tTime
        }),
        (e.globalTime = function (t) {
          for (var e = this, r = arguments.length ? t : e.rawTime(); e; )
            (r = e._start + r / (e._ts || 1)), (e = e._dp)
          return r
        }),
        (e.repeat = function (t) {
          return arguments.length
            ? ((this._repeat = t === 1 / 0 ? -2 : t), Wt(this))
            : -2 === this._repeat
            ? 1 / 0
            : this._repeat
        }),
        (e.repeatDelay = function (t) {
          return arguments.length
            ? ((this._rDelay = t), Wt(this))
            : this._rDelay
        }),
        (e.yoyo = function (t) {
          return arguments.length ? ((this._yoyo = t), this) : this._yoyo
        }),
        (e.seek = function (t, e) {
          return this.totalTime(Ft(this, t), C(e))
        }),
        (e.restart = function (t, e) {
          return this.play().totalTime(t ? -this._delay : 0, C(e))
        }),
        (e.play = function (t, e) {
          return null != t && this.seek(t, e), this.reversed(!1).paused(!1)
        }),
        (e.reverse = function (t, e) {
          return (
            null != t && this.seek(t || this.totalDuration(), e),
            this.reversed(!0).paused(!1)
          )
        }),
        (e.pause = function (t, e) {
          return null != t && this.seek(t, e), this.paused(!0)
        }),
        (e.resume = function () {
          return this.paused(!1)
        }),
        (e.reversed = function (t) {
          return arguments.length
            ? (!!t !== this.reversed() &&
                this.timeScale(-this._rts || (t ? -1e-8 : 0)),
              this)
            : this._rts < 0
        }),
        (e.invalidate = function () {
          return (this._initted = this._act = 0), (this._zTime = -1e-8), this
        }),
        (e.isActive = function () {
          var t,
            e = this.parent || this._dp,
            r = this._start
          return !(
            e &&
            !(
              this._ts &&
              this._initted &&
              e.isActive() &&
              (t = e.rawTime(!0)) >= r &&
              t < this.endTime(!0) - _
            )
          )
        }),
        (e.eventCallback = function (t, e, r) {
          var n = this.vars
          return arguments.length > 1
            ? (e
                ? ((n[t] = e),
                  r && (n[t + 'Params'] = r),
                  'onUpdate' === t && (this._onUpdate = e))
                : delete n[t],
              this)
            : n[t]
        }),
        (e.then = function (t) {
          var e = this
          return new Promise(function (r) {
            var n = k(t) ? t : ft,
              i = function () {
                var t = e.then
                ;(e.then = null),
                  k(n) && (n = n(e)) && (n.then || n === e) && (e.then = t),
                  r(n),
                  (e.then = t)
              }
            ;(e._initted && 1 === e.totalProgress() && e._ts >= 0) ||
            (!e._tTime && e._ts < 0)
              ? i()
              : (e._prom = i)
          })
        }),
        (e.kill = function () {
          re(this)
        }),
        t
      )
    })()
  ht(Oe.prototype, {
    _time: 0,
    _start: 0,
    _end: 0,
    _tTime: 0,
    _tDur: 0,
    _dirty: 0,
    _repeat: 0,
    _yoyo: !1,
    parent: null,
    _initted: !1,
    _rDelay: 0,
    _ts: 1,
    _dp: 0,
    ratio: 0,
    _zTime: -1e-8,
    _prom: 0,
    _ps: !1,
    _rts: 1,
  })
  var Ee = (function (r) {
    function i(e, n) {
      var i
      return (
        void 0 === e && (e = {}),
        ((i = r.call(this, e, n) || this).labels = {}),
        (i.smoothChildTiming = !!e.smoothChildTiming),
        (i.autoRemoveChildren = !!e.autoRemoveChildren),
        (i._sort = C(e.sortChildren)),
        i.parent && Ct(i.parent, t(i)),
        e.scrollTrigger && Mt(t(i), e.scrollTrigger),
        i
      )
    }
    e(i, r)
    var o = i.prototype
    return (
      (o.to = function (t, e, r) {
        return (
          new Be(t, lt(arguments, 0, this), Ft(this, O(e) ? arguments[3] : r)),
          this
        )
      }),
      (o.from = function (t, e, r) {
        return (
          new Be(t, lt(arguments, 1, this), Ft(this, O(e) ? arguments[3] : r)),
          this
        )
      }),
      (o.fromTo = function (t, e, r, n) {
        return (
          new Be(t, lt(arguments, 2, this), Ft(this, O(e) ? arguments[4] : n)),
          this
        )
      }),
      (o.set = function (t, e, r) {
        return (
          (e.duration = 0),
          (e.parent = this),
          vt(e).repeatDelay || (e.repeat = 0),
          (e.immediateRender = !!e.immediateRender),
          new Be(t, e, Ft(this, r), 1),
          this
        )
      }),
      (o.call = function (t, e, r) {
        return Lt(this, Be.delayedCall(0, t, e), Ft(this, r))
      }),
      (o.staggerTo = function (t, e, r, n, i, o, s) {
        return (
          (r.duration = e),
          (r.stagger = r.stagger || n),
          (r.onComplete = o),
          (r.onCompleteParams = s),
          (r.parent = this),
          new Be(t, r, Ft(this, i)),
          this
        )
      }),
      (o.staggerFrom = function (t, e, r, n, i, o, s) {
        return (
          (r.runBackwards = 1),
          (vt(r).immediateRender = C(r.immediateRender)),
          this.staggerTo(t, e, r, n, i, o, s)
        )
      }),
      (o.staggerFromTo = function (t, e, r, n, i, o, s, a) {
        return (
          (n.startAt = r),
          (vt(n).immediateRender = C(n.immediateRender)),
          this.staggerTo(t, e, n, i, o, s, a)
        )
      }),
      (o.render = function (t, e, r) {
        var i,
          o,
          s,
          a,
          u,
          l,
          c,
          d,
          p,
          f,
          h,
          g,
          m = this._time,
          y = this._dirty ? this.totalDuration() : this._tDur,
          v = this._dur,
          w = this !== n && t > y - _ && t >= 0 ? y : t < _ ? 0 : t,
          b = this._zTime < 0 != t < 0 && (this._initted || !v)
        if (w !== this._tTime || r || b) {
          if (
            (m !== this._time &&
              v &&
              ((w += this._time - m), (t += this._time - m)),
            (i = w),
            (p = this._start),
            (l = !(d = this._ts)),
            b && (v || (m = this._zTime), (t || !e) && (this._zTime = t)),
            this._repeat)
          ) {
            if (
              ((h = this._yoyo),
              (u = v + this._rDelay),
              this._repeat < -1 && t < 0)
            )
              return this.totalTime(100 * u + t, e, r)
            if (
              ((i = at(w % u)),
              w === y
                ? ((a = this._repeat), (i = v))
                : ((a = ~~(w / u)) && a === w / u && ((i = v), a--),
                  i > v && (i = v)),
              (f = kt(this._tTime, u)),
              !m && this._tTime && f !== a && (f = a),
              h && 1 & a && ((i = v - i), (g = 1)),
              a !== f && !this._lock)
            ) {
              var x = h && 1 & f,
                T = x === (h && 1 & a)
              if (
                (a < f && (x = !x),
                (m = x ? 0 : v),
                (this._lock = 1),
                (this.render(m || (g ? 0 : at(a * u)), e, !v)._lock = 0),
                !e && this.parent && ee(this, 'onRepeat'),
                this.vars.repeatRefresh && !g && (this.invalidate()._lock = 1),
                m !== this._time || l !== !this._ts)
              )
                return this
              if (
                ((v = this._dur),
                (y = this._tDur),
                T &&
                  ((this._lock = 2),
                  (m = x ? v : -1e-4),
                  this.render(m, !0),
                  this.vars.repeatRefresh && !g && this.invalidate()),
                (this._lock = 0),
                !this._ts && !l)
              )
                return this
              we(this, g)
            }
          }
          if (
            (this._hasPause &&
              !this._forcing &&
              this._lock < 2 &&
              (c = (function (t, e, r) {
                var n
                if (r > e)
                  for (n = t._first; n && n._start <= r; ) {
                    if (!n._dur && 'isPause' === n.data && n._start > e)
                      return n
                    n = n._next
                  }
                else
                  for (n = t._last; n && n._start >= r; ) {
                    if (!n._dur && 'isPause' === n.data && n._start < e)
                      return n
                    n = n._prev
                  }
              })(this, at(m), at(i))) &&
              (w -= i - (i = c._start)),
            (this._tTime = w),
            (this._time = i),
            (this._act = !d),
            this._initted ||
              ((this._onUpdate = this.vars.onUpdate),
              (this._initted = 1),
              (this._zTime = t),
              (m = 0)),
            !m && (i || (!v && t >= 0)) && !e && ee(this, 'onStart'),
            i >= m && t >= 0)
          )
            for (o = this._first; o; ) {
              if (
                ((s = o._next), (o._act || i >= o._start) && o._ts && c !== o)
              ) {
                if (o.parent !== this) return this.render(t, e, r)
                if (
                  (o.render(
                    o._ts > 0
                      ? (i - o._start) * o._ts
                      : (o._dirty ? o.totalDuration() : o._tDur) +
                          (i - o._start) * o._ts,
                    e,
                    r
                  ),
                  i !== this._time || (!this._ts && !l))
                ) {
                  ;(c = 0), s && (w += this._zTime = -1e-8)
                  break
                }
              }
              o = s
            }
          else {
            o = this._last
            for (var S = t < 0 ? t : i; o; ) {
              if (
                ((s = o._prev), (o._act || S <= o._end) && o._ts && c !== o)
              ) {
                if (o.parent !== this) return this.render(t, e, r)
                if (
                  (o.render(
                    o._ts > 0
                      ? (S - o._start) * o._ts
                      : (o._dirty ? o.totalDuration() : o._tDur) +
                          (S - o._start) * o._ts,
                    e,
                    r
                  ),
                  i !== this._time || (!this._ts && !l))
                ) {
                  ;(c = 0), s && (w += this._zTime = S ? -1e-8 : _)
                  break
                }
              }
              o = s
            }
          }
          if (
            c &&
            !e &&
            (this.pause(),
            (c.render(i >= m ? 0 : -1e-8)._zTime = i >= m ? 1 : -1),
            this._ts)
          )
            return (this._start = p), Et(this), this.render(t, e, r)
          this._onUpdate && !e && ee(this, 'onUpdate', !0),
            ((w === y && y >= this.totalDuration()) || (!w && m)) &&
              ((p !== this._start && Math.abs(d) === Math.abs(this._ts)) ||
                this._lock ||
                ((t || !v) &&
                  ((w === y && this._ts > 0) || (!w && this._ts < 0)) &&
                  bt(this, 1),
                e ||
                  (t < 0 && !m) ||
                  (!w && !m) ||
                  (ee(this, w === y ? 'onComplete' : 'onReverseComplete', !0),
                  this._prom &&
                    !(w < y && this.timeScale() > 0) &&
                    this._prom())))
        }
        return this
      }),
      (o.add = function (t, e) {
        var r = this
        if ((O(e) || (e = Ft(this, e)), !(t instanceof Oe))) {
          if (R(t))
            return (
              t.forEach(function (t) {
                return r.add(t, e)
              }),
              this
            )
          if (A(t)) return this.addLabel(t, e)
          if (!k(t)) return this
          t = Be.delayedCall(0, t)
        }
        return this !== t ? Lt(this, t, e) : this
      }),
      (o.getChildren = function (t, e, r, n) {
        void 0 === t && (t = !0),
          void 0 === e && (e = !0),
          void 0 === r && (r = !0),
          void 0 === n && (n = -1e8)
        for (var i = [], o = this._first; o; )
          o._start >= n &&
            (o instanceof Be
              ? e && i.push(o)
              : (r && i.push(o),
                t && i.push.apply(i, o.getChildren(!0, e, r)))),
            (o = o._next)
        return i
      }),
      (o.getById = function (t) {
        for (var e = this.getChildren(1, 1, 1), r = e.length; r--; )
          if (e[r].vars.id === t) return e[r]
      }),
      (o.remove = function (t) {
        return A(t)
          ? this.removeLabel(t)
          : k(t)
          ? this.killTweensOf(t)
          : (wt(this, t),
            t === this._recent && (this._recent = this._last),
            xt(this))
      }),
      (o.totalTime = function (t, e) {
        return arguments.length
          ? ((this._forcing = 1),
            !this._dp &&
              this._ts &&
              (this._start = at(
                fe.time -
                  (this._ts > 0
                    ? t / this._ts
                    : (this.totalDuration() - t) / -this._ts)
              )),
            r.prototype.totalTime.call(this, t, e),
            (this._forcing = 0),
            this)
          : this._tTime
      }),
      (o.addLabel = function (t, e) {
        return (this.labels[t] = Ft(this, e)), this
      }),
      (o.removeLabel = function (t) {
        return delete this.labels[t], this
      }),
      (o.addPause = function (t, e, r) {
        var n = Be.delayedCall(0, e || G, r)
        return (
          (n.data = 'isPause'), (this._hasPause = 1), Lt(this, n, Ft(this, t))
        )
      }),
      (o.removePause = function (t) {
        var e = this._first
        for (t = Ft(this, t); e; )
          e._start === t && 'isPause' === e.data && bt(e), (e = e._next)
      }),
      (o.killTweensOf = function (t, e, r) {
        for (var n = this.getTweensOf(t, r), i = n.length; i--; )
          qe !== n[i] && n[i].kill(t, e)
        return this
      }),
      (o.getTweensOf = function (t, e) {
        for (var r, n = [], i = Ut(t), o = this._first, s = O(e); o; )
          o instanceof Be
            ? ut(o._targets, i) &&
              (s
                ? (!qe || (o._initted && o._ts)) &&
                  o.globalTime(0) <= e &&
                  o.globalTime(o.totalDuration()) > e
                : !e || o.isActive()) &&
              n.push(o)
            : (r = o.getTweensOf(i, e)).length && n.push.apply(n, r),
            (o = o._next)
        return n
      }),
      (o.tweenTo = function (t, e) {
        e = e || {}
        var r = this,
          n = Ft(r, t),
          i = e,
          o = i.startAt,
          s = i.onStart,
          a = i.onStartParams,
          u = i.immediateRender,
          l = Be.to(
            r,
            ht(
              {
                ease: 'none',
                lazy: !1,
                immediateRender: !1,
                time: n,
                overwrite: 'auto',
                duration:
                  e.duration ||
                  Math.abs(
                    (n - (o && 'time' in o ? o.time : r._time)) / r.timeScale()
                  ) ||
                  _,
                onStart: function () {
                  r.pause()
                  var t = e.duration || Math.abs((n - r._time) / r.timeScale())
                  l._dur !== t && Dt(l, t, 0, 1).render(l._time, !0, !0),
                    s && s.apply(l, a || [])
                },
              },
              e
            )
          )
        return u ? l.render(0) : l
      }),
      (o.tweenFromTo = function (t, e, r) {
        return this.tweenTo(e, ht({ startAt: { time: Ft(this, t) } }, r))
      }),
      (o.recent = function () {
        return this._recent
      }),
      (o.nextLabel = function (t) {
        return void 0 === t && (t = this._time), te(this, Ft(this, t))
      }),
      (o.previousLabel = function (t) {
        return void 0 === t && (t = this._time), te(this, Ft(this, t), 1)
      }),
      (o.currentLabel = function (t) {
        return arguments.length
          ? this.seek(t, !0)
          : this.previousLabel(this._time + _)
      }),
      (o.shiftChildren = function (t, e, r) {
        void 0 === r && (r = 0)
        for (var n, i = this._first, o = this.labels; i; )
          i._start >= r && ((i._start += t), (i._end += t)), (i = i._next)
        if (e) for (n in o) o[n] >= r && (o[n] += t)
        return xt(this)
      }),
      (o.invalidate = function () {
        var t = this._first
        for (this._lock = 0; t; ) t.invalidate(), (t = t._next)
        return r.prototype.invalidate.call(this)
      }),
      (o.clear = function (t) {
        void 0 === t && (t = !0)
        for (var e, r = this._first; r; ) (e = r._next), this.remove(r), (r = e)
        return (
          this._dp && (this._time = this._tTime = this._pTime = 0),
          t && (this.labels = {}),
          xt(this)
        )
      }),
      (o.totalDuration = function (t) {
        var e,
          r,
          i,
          o = 0,
          s = this,
          a = s._last,
          u = y
        if (arguments.length)
          return s.timeScale(
            (s._repeat < 0 ? s.duration() : s.totalDuration()) /
              (s.reversed() ? -t : t)
          )
        if (s._dirty) {
          for (i = s.parent; a; )
            (e = a._prev),
              a._dirty && a.totalDuration(),
              (r = a._start) > u && s._sort && a._ts && !s._lock
                ? ((s._lock = 1), (Lt(s, a, r - a._delay, 1)._lock = 0))
                : (u = r),
              r < 0 &&
                a._ts &&
                ((o -= r),
                ((!i && !s._dp) || (i && i.smoothChildTiming)) &&
                  ((s._start += r / s._ts), (s._time -= r), (s._tTime -= r)),
                s.shiftChildren(-r, !1, -Infinity),
                (u = 0)),
              a._end > o && a._ts && (o = a._end),
              (a = e)
          Dt(s, s === n && s._time > o ? s._time : o, 1, 1), (s._dirty = 0)
        }
        return s._tDur
      }),
      (i.updateRoot = function (t) {
        if ((n._ts && (dt(n, Ot(t, n)), (u = fe.frame)), fe.frame >= tt)) {
          tt += g.autoSleep || 120
          var e = n._first
          if ((!e || !e._ts) && g.autoSleep && fe._listeners.length < 2) {
            for (; e && !e._ts; ) e = e._next
            e || fe.sleep()
          }
        }
      }),
      i
    )
  })(Oe)
  ht(Ee.prototype, { _lock: 0, _hasPause: 0, _forcing: 0 })
  var qe,
    Ce = function (t, e, r, n, i, o, s) {
      var a,
        u,
        l,
        c,
        d,
        p,
        f,
        h,
        g = new $e(this._pt, t, e, 0, 1, Ue, null, i),
        m = 0,
        y = 0
      for (
        g.b = r,
          g.e = n,
          r += '',
          (f = ~(n += '').indexOf('random(')) && (n = Jt(n)),
          o && (o((h = [r, n]), t, e), (r = h[0]), (n = h[1])),
          u = r.match(F) || [];
        (a = F.exec(n));

      )
        (c = a[0]),
          (d = n.substring(m, a.index)),
          l ? (l = (l + 1) % 5) : 'rgba(' === d.substr(-5) && (l = 1),
          c !== u[y++] &&
            ((p = parseFloat(u[y - 1]) || 0),
            (g._pt = {
              _next: g._pt,
              p: d || 1 === y ? d : ',',
              s: p,
              c:
                '=' === c.charAt(1)
                  ? parseFloat(c.substr(2)) * ('-' === c.charAt(0) ? -1 : 1)
                  : parseFloat(c) - p,
              m: l && l < 4 ? Math.round : 0,
            }),
            (m = F.lastIndex))
      return (
        (g.c = m < n.length ? n.substring(m, n.length) : ''),
        (g.fp = s),
        (I.test(n) || f) && (g.e = 0),
        (this._pt = g),
        g
      )
    },
    Le = function (t, e, r, n, i, o, s, a, u) {
      k(n) && (n = n(i || 0, t, o))
      var l,
        c = t[e],
        d =
          'get' !== r
            ? r
            : k(c)
            ? u
              ? t[
                  e.indexOf('set') || !k(t['get' + e.substr(3)])
                    ? e
                    : 'get' + e.substr(3)
                ](u)
              : t[e]()
            : c,
        p = k(c) ? (u ? ze : Ie) : Fe
      if (
        (A(n) &&
          (~n.indexOf('random(') && (n = Jt(n)),
          '=' === n.charAt(1) &&
            (n =
              parseFloat(d) +
              parseFloat(n.substr(2)) * ('-' === n.charAt(0) ? -1 : 1) +
              (Ht(d) || 0))),
        d !== n)
      )
        return isNaN(d * n)
          ? (!c && !(e in t) && U(e, n),
            Ce.call(this, t, e, d, n, p, a || g.stringFilter, u))
          : ((l = new $e(
              this._pt,
              t,
              e,
              +d || 0,
              n - (d || 0),
              'boolean' == typeof c ? Ve : Ye,
              0,
              p
            )),
            u && (l.fp = u),
            s && l.modifier(s, this, t),
            (this._pt = l))
    },
    Me = function (t, e, r, n, i, o) {
      var s, a, u, c
      if (
        J[t] &&
        !1 !==
          (s = new J[t]()).init(
            i,
            s.rawVars
              ? e[t]
              : (function (t, e, r, n, i) {
                  if (
                    (k(t) && (t = Re(t, i, e, r, n)),
                    !q(t) || (t.style && t.nodeType) || R(t) || P(t))
                  )
                    return A(t) ? Re(t, i, e, r, n) : t
                  var o,
                    s = {}
                  for (o in t) s[o] = Re(t[o], i, e, r, n)
                  return s
                })(e[t], n, i, o, r),
            r,
            n,
            o
          ) &&
        ((r._pt = a = new $e(r._pt, i, t, 0, 1, s.render, s, 0, s.priority)),
        r !== l)
      )
        for (u = r._ptLookup[r._targets.indexOf(i)], c = s._props.length; c--; )
          u[s._props[c]] = a
      return s
    },
    Pe = function t(e, i) {
      var o,
        s,
        a,
        u,
        l,
        c,
        d,
        p,
        f,
        h,
        g,
        y,
        v,
        w = e.vars,
        b = w.ease,
        x = w.startAt,
        T = w.immediateRender,
        S = w.lazy,
        A = w.onUpdate,
        k = w.onUpdateParams,
        O = w.callbackScope,
        E = w.runBackwards,
        q = w.yoyoEase,
        L = w.keyframes,
        M = w.autoRevert,
        P = e._dur,
        R = e._startAt,
        D = e._targets,
        W = e.parent,
        B = W && 'nested' === W.data ? W.parent._targets : D,
        F = 'auto' === e._overwrite && !r,
        I = e.timeline
      if (
        (I && (!L || !b) && (b = 'none'),
        (e._ease = be(b, m.ease)),
        (e._yEase = q ? ve(be(!0 === q ? b : q, m.ease)) : 0),
        q &&
          e._yoyo &&
          !e._repeat &&
          ((q = e._yEase), (e._yEase = e._ease), (e._ease = q)),
        !I)
      ) {
        if (
          ((y = (p = D[0] ? it(D[0]).harness : 0) && w[p.prop]),
          (o = _t(w, Z)),
          R && R.render(-1, !0).kill(),
          x)
        ) {
          if (
            (bt(
              (e._startAt = Be.set(
                D,
                ht(
                  {
                    data: 'isStart',
                    overwrite: !1,
                    parent: W,
                    immediateRender: !0,
                    lazy: C(S),
                    startAt: null,
                    delay: 0,
                    onUpdate: A,
                    onUpdateParams: k,
                    callbackScope: O,
                    stagger: 0,
                  },
                  x
                )
              ))
            ),
            T)
          )
            if (i > 0) M || (e._startAt = 0)
            else if (P && !(i < 0 && R)) return void (i && (e._zTime = i))
        } else if (E && P)
          if (R) !M && (e._startAt = 0)
          else if (
            (i && (T = !1),
            (a = ht(
              {
                overwrite: !1,
                data: 'isFromStart',
                lazy: T && C(S),
                immediateRender: T,
                stagger: 0,
                parent: W,
              },
              o
            )),
            y && (a[p.prop] = y),
            bt((e._startAt = Be.set(D, a))),
            T)
          ) {
            if (!i) return
          } else t(e._startAt, _)
        for (
          e._pt = 0, S = (P && C(S)) || (S && !P), s = 0;
          s < D.length;
          s++
        ) {
          if (
            ((d = (l = D[s])._gsap || nt(D)[s]._gsap),
            (e._ptLookup[s] = h = {}),
            $[d.id] && Q.length && ct(),
            (g = B === D ? s : B.indexOf(l)),
            p &&
              !1 !== (f = new p()).init(l, y || o, e, g, B) &&
              ((e._pt = u =
                new $e(e._pt, l, f.name, 0, 1, f.render, f, 0, f.priority)),
              f._props.forEach(function (t) {
                h[t] = u
              }),
              f.priority && (c = 1)),
            !p || y)
          )
            for (a in o)
              J[a] && (f = Me(a, o, e, g, l, B))
                ? f.priority && (c = 1)
                : (h[a] = u =
                    Le.call(e, l, a, 'get', o[a], g, B, 0, w.stringFilter))
          e._op && e._op[s] && e.kill(l, e._op[s]),
            F &&
              e._pt &&
              ((qe = e),
              n.killTweensOf(l, h, e.globalTime(0)),
              (v = !e.parent),
              (qe = 0)),
            e._pt && S && ($[d.id] = 1)
        }
        c && Qe(e), e._onInit && e._onInit(e)
      }
      ;(e._from = !I && !!w.runBackwards),
        (e._onUpdate = A),
        (e._initted = (!e._op || e._pt) && !v)
    },
    Re = function (t, e, r, n, i) {
      return k(t)
        ? t.call(e, r, n, i)
        : A(t) && ~t.indexOf('random(')
        ? Jt(t)
        : t
    },
    De = rt + 'repeat,repeatDelay,yoyo,repeatRefresh,yoyoEase',
    We = (De + ',id,stagger,delay,duration,paused,scrollTrigger').split(','),
    Be = (function (i) {
      function o(e, o, s, a) {
        var u
        'number' == typeof o && ((s.duration = o), (o = s), (s = null))
        var l,
          c,
          d,
          p,
          f,
          h,
          m,
          y,
          _ = (u = i.call(this, a ? o : vt(o), s) || this).vars,
          v = _.duration,
          w = _.delay,
          b = _.immediateRender,
          x = _.stagger,
          T = _.overwrite,
          S = _.keyframes,
          A = _.defaults,
          k = _.scrollTrigger,
          E = _.yoyoEase,
          L = u.parent,
          D = (R(e) || P(e) ? O(e[0]) : 'length' in o) ? [e] : Ut(e)
        if (
          ((u._targets = D.length
            ? nt(D)
            : X(
                'GSAP target ' + e + ' not found. https://greensock.com',
                !g.nullTargetWarn
              ) || []),
          (u._ptLookup = []),
          (u._overwrite = T),
          S || x || M(v) || M(w))
        ) {
          if (
            ((o = u.vars),
            (l = u.timeline =
              new Ee({ data: 'nested', defaults: A || {} })).kill(),
            (l.parent = l._dp = t(u)),
            (l._start = 0),
            S)
          )
            ht(l.vars.defaults, { ease: 'none' }),
              S.forEach(function (t) {
                return l.to(D, t, '>')
              })
          else {
            if (((p = D.length), (m = x ? jt(x) : G), q(x)))
              for (f in x) ~De.indexOf(f) && (y || (y = {}), (y[f] = x[f]))
            for (c = 0; c < p; c++) {
              for (f in ((d = {}), o)) We.indexOf(f) < 0 && (d[f] = o[f])
              ;(d.stagger = 0),
                E && (d.yoyoEase = E),
                y && mt(d, y),
                (h = D[c]),
                (d.duration = +Re(v, t(u), c, h, D)),
                (d.delay = (+Re(w, t(u), c, h, D) || 0) - u._delay),
                !x &&
                  1 === p &&
                  d.delay &&
                  ((u._delay = w = d.delay), (u._start += w), (d.delay = 0)),
                l.to(h, d, m(c, h, D))
            }
            l.duration() ? (v = w = 0) : (u.timeline = 0)
          }
          v || u.duration((v = l.duration()))
        } else u.timeline = 0
        return (
          !0 !== T || r || ((qe = t(u)), n.killTweensOf(D), (qe = 0)),
          L && Ct(L, t(u)),
          (b ||
            (!v &&
              !S &&
              u._start === at(L._time) &&
              C(b) &&
              St(t(u)) &&
              'nested' !== L.data)) &&
            ((u._tTime = -1e-8), u.render(Math.max(0, -w))),
          k && Mt(t(u), k),
          u
        )
      }
      e(o, i)
      var s = o.prototype
      return (
        (s.render = function (t, e, r) {
          var n,
            i,
            o,
            s,
            a,
            u,
            l,
            c,
            d,
            p = this._time,
            f = this._tDur,
            h = this._dur,
            g = t > f - _ && t >= 0 ? f : t < _ ? 0 : t
          if (h) {
            if (
              g !== this._tTime ||
              !t ||
              r ||
              (!this._initted && this._tTime) ||
              (this._startAt && this._zTime < 0 != t < 0)
            ) {
              if (((n = g), (c = this.timeline), this._repeat)) {
                if (((s = h + this._rDelay), this._repeat < -1 && t < 0))
                  return this.totalTime(100 * s + t, e, r)
                if (
                  ((n = at(g % s)),
                  g === f
                    ? ((o = this._repeat), (n = h))
                    : ((o = ~~(g / s)) && o === g / s && ((n = h), o--),
                      n > h && (n = h)),
                  (u = this._yoyo && 1 & o) && ((d = this._yEase), (n = h - n)),
                  (a = kt(this._tTime, s)),
                  n === p && !r && this._initted)
                )
                  return this
                o !== a &&
                  (c && this._yEase && we(c, u),
                  !this.vars.repeatRefresh ||
                    u ||
                    this._lock ||
                    ((this._lock = r = 1),
                    (this.render(at(s * o), !0).invalidate()._lock = 0)))
              }
              if (!this._initted) {
                if (Pt(this, t < 0 ? t : n, r, e))
                  return (this._tTime = 0), this
                if (h !== this._dur) return this.render(t, e, r)
              }
              for (
                this._tTime = g,
                  this._time = n,
                  !this._act && this._ts && ((this._act = 1), (this._lazy = 0)),
                  this.ratio = l = (d || this._ease)(n / h),
                  this._from && (this.ratio = l = 1 - l),
                  n && !p && !e && ee(this, 'onStart'),
                  i = this._pt;
                i;

              )
                i.r(l, i.d), (i = i._next)
              ;(c &&
                c.render(t < 0 ? t : !n && u ? -1e-8 : c._dur * l, e, r)) ||
                (this._startAt && (this._zTime = t)),
                this._onUpdate &&
                  !e &&
                  (t < 0 && this._startAt && this._startAt.render(t, !0, r),
                  ee(this, 'onUpdate')),
                this._repeat &&
                  o !== a &&
                  this.vars.onRepeat &&
                  !e &&
                  this.parent &&
                  ee(this, 'onRepeat'),
                (g !== this._tDur && g) ||
                  this._tTime !== g ||
                  (t < 0 &&
                    this._startAt &&
                    !this._onUpdate &&
                    this._startAt.render(t, !0, !0),
                  (t || !h) &&
                    ((g === this._tDur && this._ts > 0) ||
                      (!g && this._ts < 0)) &&
                    bt(this, 1),
                  e ||
                    (t < 0 && !p) ||
                    (!g && !p) ||
                    (ee(this, g === f ? 'onComplete' : 'onReverseComplete', !0),
                    this._prom &&
                      !(g < f && this.timeScale() > 0) &&
                      this._prom()))
            }
          } else
            !(function (t, e, r, n) {
              var i,
                o,
                s,
                a = t.ratio,
                u =
                  e < 0 ||
                  (!e &&
                    ((!t._start && Rt(t)) ||
                      ((t._ts < 0 || t._dp._ts < 0) &&
                        'isFromStart' !== t.data &&
                        'isStart' !== t.data)))
                    ? 0
                    : 1,
                l = t._rDelay,
                c = 0
              if (
                (l &&
                  t._repeat &&
                  ((c = zt(0, t._tDur, e)),
                  (o = kt(c, l)),
                  (s = kt(t._tTime, l)),
                  t._yoyo && 1 & o && (u = 1 - u),
                  o !== s &&
                    ((a = 1 - u),
                    t.vars.repeatRefresh && t._initted && t.invalidate())),
                u !== a || n || t._zTime === _ || (!e && t._zTime))
              ) {
                if (!t._initted && Pt(t, e, n, r)) return
                for (
                  s = t._zTime,
                    t._zTime = e || (r ? _ : 0),
                    r || (r = e && !s),
                    t.ratio = u,
                    t._from && (u = 1 - u),
                    t._time = 0,
                    t._tTime = c,
                    r || ee(t, 'onStart'),
                    i = t._pt;
                  i;

                )
                  i.r(u, i.d), (i = i._next)
                t._startAt && e < 0 && t._startAt.render(e, !0, !0),
                  t._onUpdate && !r && ee(t, 'onUpdate'),
                  c && t._repeat && !r && t.parent && ee(t, 'onRepeat'),
                  (e >= t._tDur || e < 0) &&
                    t.ratio === u &&
                    (u && bt(t, 1),
                    r ||
                      (ee(t, u ? 'onComplete' : 'onReverseComplete', !0),
                      t._prom && t._prom()))
              } else t._zTime || (t._zTime = e)
            })(this, t, e, r)
          return this
        }),
        (s.targets = function () {
          return this._targets
        }),
        (s.invalidate = function () {
          return (
            (this._pt =
              this._op =
              this._startAt =
              this._onUpdate =
              this._lazy =
              this.ratio =
                0),
            (this._ptLookup = []),
            this.timeline && this.timeline.invalidate(),
            i.prototype.invalidate.call(this)
          )
        }),
        (s.kill = function (t, e) {
          if ((void 0 === e && (e = 'all'), !(t || (e && 'all' !== e))))
            return (this._lazy = this._pt = 0), this.parent ? re(this) : this
          if (this.timeline) {
            var r = this.timeline.totalDuration()
            return (
              this.timeline.killTweensOf(t, e, qe && !0 !== qe.vars.overwrite)
                ._first || re(this),
              this.parent &&
                r !== this.timeline.totalDuration() &&
                Dt(this, (this._dur * this.timeline._tDur) / r, 0, 1),
              this
            )
          }
          var n,
            i,
            o,
            s,
            a,
            u,
            l,
            c = this._targets,
            d = t ? Ut(t) : c,
            p = this._ptLookup,
            f = this._pt
          if (
            (!e || 'all' === e) &&
            (function (t, e) {
              for (
                var r = t.length, n = r === e.length;
                n && r-- && t[r] === e[r];

              );
              return r < 0
            })(c, d)
          )
            return 'all' === e && (this._pt = 0), re(this)
          for (
            n = this._op = this._op || [],
              'all' !== e &&
                (A(e) &&
                  ((a = {}),
                  st(e, function (t) {
                    return (a[t] = 1)
                  }),
                  (e = a)),
                (e = (function (t, e) {
                  var r,
                    n,
                    i,
                    o,
                    s = t[0] ? it(t[0]).harness : 0,
                    a = s && s.aliases
                  if (!a) return e
                  for (n in ((r = mt({}, e)), a))
                    if ((n in r))
                      for (i = (o = a[n].split(',')).length; i--; )
                        r[o[i]] = r[n]
                  return r
                })(c, e))),
              l = c.length;
            l--;

          )
            if (~d.indexOf(c[l]))
              for (a in ((i = p[l]),
              'all' === e
                ? ((n[l] = e), (s = i), (o = {}))
                : ((o = n[l] = n[l] || {}), (s = e)),
              s))
                (u = i && i[a]) &&
                  (('kill' in u.d && !0 !== u.d.kill(a)) || wt(this, u, '_pt'),
                  delete i[a]),
                  'all' !== o && (o[a] = 1)
          return this._initted && !this._pt && f && re(this), this
        }),
        (o.to = function (t, e) {
          return new o(t, e, arguments[2])
        }),
        (o.from = function (t, e) {
          return new o(t, lt(arguments, 1))
        }),
        (o.delayedCall = function (t, e, r, n) {
          return new o(e, 0, {
            immediateRender: !1,
            lazy: !1,
            overwrite: !1,
            delay: t,
            onComplete: e,
            onReverseComplete: e,
            onCompleteParams: r,
            onReverseCompleteParams: r,
            callbackScope: n,
          })
        }),
        (o.fromTo = function (t, e, r) {
          return new o(t, lt(arguments, 2))
        }),
        (o.set = function (t, e) {
          return (e.duration = 0), e.repeatDelay || (e.repeat = 0), new o(t, e)
        }),
        (o.killTweensOf = function (t, e, r) {
          return n.killTweensOf(t, e, r)
        }),
        o
      )
    })(Oe)
  ht(Be.prototype, { _targets: [], _lazy: 0, _startAt: 0, _op: 0, _onInit: 0 }),
    st('staggerTo,staggerFrom,staggerFromTo', function (t) {
      Be[t] = function () {
        var e = new Ee(),
          r = Nt.call(arguments, 0)
        return r.splice('staggerFromTo' === t ? 5 : 4, 0, 0), e[t].apply(e, r)
      }
    })
  var Fe = function (t, e, r) {
      return (t[e] = r)
    },
    Ie = function (t, e, r) {
      return t[e](r)
    },
    ze = function (t, e, r, n) {
      return t[e](n.fp, r)
    },
    He = function (t, e, r) {
      return t.setAttribute(e, r)
    },
    Ne = function (t, e) {
      return k(t[e]) ? Ie : E(t[e]) && t.setAttribute ? He : Fe
    },
    Ye = function (t, e) {
      return e.set(e.t, e.p, Math.round(1e4 * (e.s + e.c * t)) / 1e4, e)
    },
    Ve = function (t, e) {
      return e.set(e.t, e.p, !!(e.s + e.c * t), e)
    },
    Ue = function (t, e) {
      var r = e._pt,
        n = ''
      if (!t && e.b) n = e.b
      else if (1 === t && e.e) n = e.e
      else {
        for (; r; )
          (n =
            r.p +
            (r.m
              ? r.m(r.s + r.c * t)
              : Math.round(1e4 * (r.s + r.c * t)) / 1e4) +
            n),
            (r = r._next)
        n += e.c
      }
      e.set(e.t, e.p, n, e)
    },
    Xe = function (t, e) {
      for (var r = e._pt; r; ) r.r(t, r.d), (r = r._next)
    },
    je = function (t, e, r, n) {
      for (var i, o = this._pt; o; )
        (i = o._next), o.p === n && o.modifier(t, e, r), (o = i)
    },
    Ge = function (t) {
      for (var e, r, n = this._pt; n; )
        (r = n._next),
          (n.p === t && !n.op) || n.op === t
            ? wt(this, n, '_pt')
            : n.dep || (e = 1),
          (n = r)
      return !e
    },
    Ze = function (t, e, r, n) {
      n.mSet(t, e, n.m.call(n.tween, r, n.mt), n)
    },
    Qe = function (t) {
      for (var e, r, n, i, o = t._pt; o; ) {
        for (e = o._next, r = n; r && r.pr > o.pr; ) r = r._next
        ;(o._prev = r ? r._prev : i) ? (o._prev._next = o) : (n = o),
          (o._next = r) ? (r._prev = o) : (i = o),
          (o = e)
      }
      t._pt = n
    },
    $e = (function () {
      function t(t, e, r, n, i, o, s, a, u) {
        ;(this.t = e),
          (this.s = n),
          (this.c = i),
          (this.p = r),
          (this.r = o || Ye),
          (this.d = s || this),
          (this.set = a || Fe),
          (this.pr = u || 0),
          (this._next = t),
          t && (t._prev = this)
      }
      return (
        (t.prototype.modifier = function (t, e, r) {
          ;(this.mSet = this.mSet || this.set),
            (this.set = Ze),
            (this.m = t),
            (this.mt = r),
            (this.tween = e)
        }),
        t
      )
    })()
  st(
    rt +
      'parent,duration,ease,delay,overwrite,runBackwards,startAt,yoyo,immediateRender,repeat,repeatDelay,data,paused,reversed,lazy,callbackScope,stringFilter,id,yoyoEase,stagger,inherit,repeatRefresh,keyframes,autoRevert,scrollTrigger',
    function (t) {
      return (Z[t] = 1)
    }
  ),
    (N.TweenMax = N.TweenLite = Be),
    (N.TimelineLite = N.TimelineMax = Ee),
    (n = new Ee({
      sortChildren: !1,
      defaults: m,
      autoRemoveChildren: !0,
      id: 'root',
      smoothChildTiming: !0,
    })),
    (g.stringFilter = pe)
  var Je = {
    registerPlugin: function () {
      for (var t = arguments.length, e = new Array(t), r = 0; r < t; r++)
        e[r] = arguments[r]
      e.forEach(function (t) {
        return ne(t)
      })
    },
    timeline: function (t) {
      return new Ee(t)
    },
    getTweensOf: function (t, e) {
      return n.getTweensOf(t, e)
    },
    getProperty: function (t, e, r, n) {
      A(t) && (t = Ut(t)[0])
      var i = it(t || {}).get,
        o = r ? ft : pt
      return (
        'native' === r && (r = ''),
        t
          ? e
            ? o(((J[e] && J[e].get) || i)(t, e, r, n))
            : function (e, r, n) {
                return o(((J[e] && J[e].get) || i)(t, e, r, n))
              }
          : t
      )
    },
    quickSetter: function (t, e, r) {
      if ((t = Ut(t)).length > 1) {
        var n = t.map(function (t) {
            return er.quickSetter(t, e, r)
          }),
          i = n.length
        return function (t) {
          for (var e = i; e--; ) n[e](t)
        }
      }
      t = t[0] || {}
      var o = J[e],
        s = it(t),
        a = (s.harness && (s.harness.aliases || {})[e]) || e,
        u = o
          ? function (e) {
              var n = new o()
              ;(l._pt = 0),
                n.init(t, r ? e + r : e, l, 0, [t]),
                n.render(1, n),
                l._pt && Xe(1, l)
            }
          : s.set(t, a)
      return o
        ? u
        : function (e) {
            return u(t, a, r ? e + r : e, s, 1)
          }
    },
    isTweening: function (t) {
      return n.getTweensOf(t, !0).length > 0
    },
    defaults: function (t) {
      return t && t.ease && (t.ease = be(t.ease, m.ease)), yt(m, t || {})
    },
    config: function (t) {
      return yt(g, t || {})
    },
    registerEffect: function (t) {
      var e = t.name,
        r = t.effect,
        n = t.plugins,
        i = t.defaults,
        o = t.extendTimeline
      ;(n || '').split(',').forEach(function (t) {
        return (
          t && !J[t] && !N[t] && X(e + ' effect requires ' + t + ' plugin.')
        )
      }),
        (K[e] = function (t, e, n) {
          return r(Ut(t), ht(e || {}, i), n)
        }),
        o &&
          (Ee.prototype[e] = function (t, r, n) {
            return this.add(K[e](t, q(r) ? r : (n = r) && {}, this), n)
          })
    },
    registerEase: function (t, e) {
      ge[t] = be(e)
    },
    parseEase: function (t, e) {
      return arguments.length ? be(t, e) : ge
    },
    getById: function (t) {
      return n.getById(t)
    },
    exportRoot: function (t, e) {
      void 0 === t && (t = {})
      var r,
        i,
        o = new Ee(t)
      for (
        o.smoothChildTiming = C(t.smoothChildTiming),
          n.remove(o),
          o._dp = 0,
          o._time = o._tTime = n._time,
          r = n._first;
        r;

      )
        (i = r._next),
          (!e &&
            !r._dur &&
            r instanceof Be &&
            r.vars.onComplete === r._targets[0]) ||
            Lt(o, r, r._start - r._delay),
          (r = i)
      return Lt(n, o, 0), o
    },
    utils: {
      wrap: function t(e, r, n) {
        var i = r - e
        return R(e)
          ? $t(e, t(0, e.length), r)
          : It(n, function (t) {
              return ((i + ((t - e) % i)) % i) + e
            })
      },
      wrapYoyo: function t(e, r, n) {
        var i = r - e,
          o = 2 * i
        return R(e)
          ? $t(e, t(0, e.length - 1), r)
          : It(n, function (t) {
              return e + ((t = (o + ((t - e) % o)) % o || 0) > i ? o - t : t)
            })
      },
      distribute: jt,
      random: Qt,
      snap: Zt,
      normalize: function (t, e, r) {
        return Kt(t, e, 0, 1, r)
      },
      getUnit: Ht,
      clamp: function (t, e, r) {
        return It(r, function (r) {
          return zt(t, e, r)
        })
      },
      splitColor: ae,
      toArray: Ut,
      mapRange: Kt,
      pipe: function () {
        for (var t = arguments.length, e = new Array(t), r = 0; r < t; r++)
          e[r] = arguments[r]
        return function (t) {
          return e.reduce(function (t, e) {
            return e(t)
          }, t)
        }
      },
      unitize: function (t, e) {
        return function (r) {
          return t(parseFloat(r)) + (e || Ht(r))
        }
      },
      interpolate: function t(e, r, n, i) {
        var o = isNaN(e + r)
          ? 0
          : function (t) {
              return (1 - t) * e + t * r
            }
        if (!o) {
          var s,
            a,
            u,
            l,
            c,
            d = A(e),
            p = {}
          if ((!0 === n && (i = 1) && (n = null), d))
            (e = { p: e }), (r = { p: r })
          else if (R(e) && !R(r)) {
            for (u = [], l = e.length, c = l - 2, a = 1; a < l; a++)
              u.push(t(e[a - 1], e[a]))
            l--,
              (o = function (t) {
                t *= l
                var e = Math.min(c, ~~t)
                return u[e](t - e)
              }),
              (n = r)
          } else i || (e = mt(R(e) ? [] : {}, e))
          if (!u) {
            for (s in r) Le.call(p, e, s, 'get', r[s])
            o = function (t) {
              return Xe(t, p) || (d ? e.p : e)
            }
          }
        }
        return It(n, o)
      },
      shuffle: Xt,
    },
    install: V,
    effects: K,
    ticker: fe,
    updateRoot: Ee.updateRoot,
    plugins: J,
    globalTimeline: n,
    core: {
      PropTween: $e,
      globals: j,
      Tween: Be,
      Timeline: Ee,
      Animation: Oe,
      getCache: it,
      _removeLinkedListItem: wt,
      suppressOverwrites: function (t) {
        return (r = t)
      },
    },
  }
  st('to,from,fromTo,delayedCall,set,killTweensOf', function (t) {
    return (Je[t] = Be[t])
  }),
    fe.add(Ee.updateRoot),
    (l = Je.to({}, { duration: 0 }))
  var Ke = function (t, e) {
      for (var r = t._pt; r && r.p !== e && r.op !== e && r.fp !== e; )
        r = r._next
      return r
    },
    tr = function (t, e) {
      return {
        name: t,
        rawVars: 1,
        init: function (t, r, n) {
          n._onInit = function (t) {
            var n, i
            if (
              (A(r) &&
                ((n = {}),
                st(r, function (t) {
                  return (n[t] = 1)
                }),
                (r = n)),
              e)
            ) {
              for (i in ((n = {}), r)) n[i] = e(r[i])
              r = n
            }
            !(function (t, e) {
              var r,
                n,
                i,
                o = t._targets
              for (r in e)
                for (n = o.length; n--; )
                  (i = t._ptLookup[n][r]) &&
                    (i = i.d) &&
                    (i._pt && (i = Ke(i, r)),
                    i && i.modifier && i.modifier(e[r], t, o[n], r))
            })(t, r)
          }
        },
      }
    },
    er =
      Je.registerPlugin(
        {
          name: 'attr',
          init: function (t, e, r, n, i) {
            var o, s
            for (o in e)
              (s = this.add(
                t,
                'setAttribute',
                (t.getAttribute(o) || 0) + '',
                e[o],
                n,
                i,
                0,
                0,
                o
              )) && (s.op = o),
                this._props.push(o)
          },
        },
        {
          name: 'endArray',
          init: function (t, e) {
            for (var r = e.length; r--; ) this.add(t, r, t[r] || 0, e[r])
          },
        },
        tr('roundProps', Gt),
        tr('modifiers'),
        tr('snap', Zt)
      ) || Je
  ;(Be.version = Ee.version = er.version = '3.6.0'), (a = 1), L() && he()
  /*!
   * CSSPlugin 3.6.0
   * https://greensock.com
   *
   * Copyright 2008-2021, GreenSock. All rights reserved.
   * Subject to the terms at https://greensock.com/standard-license or for
   * Club GreenSock members, the agreement issued with that membership.
   * @author: Jack Doyle, jack@greensock.com
   */
  var rr,
    nr,
    ir,
    or,
    sr,
    ar,
    ur,
    lr,
    cr = {},
    dr = 180 / Math.PI,
    pr = Math.PI / 180,
    fr = Math.atan2,
    hr = /([A-Z])/g,
    gr = /(?:left|right|width|margin|padding|x)/i,
    mr = /[\s,\(]\S/,
    yr = {
      autoAlpha: 'opacity,visibility',
      scale: 'scaleX,scaleY',
      alpha: 'opacity',
    },
    _r = function (t, e) {
      return e.set(e.t, e.p, Math.round(1e4 * (e.s + e.c * t)) / 1e4 + e.u, e)
    },
    vr = function (t, e) {
      return e.set(
        e.t,
        e.p,
        1 === t ? e.e : Math.round(1e4 * (e.s + e.c * t)) / 1e4 + e.u,
        e
      )
    },
    wr = function (t, e) {
      return e.set(
        e.t,
        e.p,
        t ? Math.round(1e4 * (e.s + e.c * t)) / 1e4 + e.u : e.b,
        e
      )
    },
    br = function (t, e) {
      var r = e.s + e.c * t
      e.set(e.t, e.p, ~~(r + (r < 0 ? -0.5 : 0.5)) + e.u, e)
    },
    xr = function (t, e) {
      return e.set(e.t, e.p, t ? e.e : e.b, e)
    },
    Tr = function (t, e) {
      return e.set(e.t, e.p, 1 !== t ? e.b : e.e, e)
    },
    Sr = function (t, e, r) {
      return (t.style[e] = r)
    },
    Ar = function (t, e, r) {
      return t.style.setProperty(e, r)
    },
    kr = function (t, e, r) {
      return (t._gsap[e] = r)
    },
    Or = function (t, e, r) {
      return (t._gsap.scaleX = t._gsap.scaleY = r)
    },
    Er = function (t, e, r, n, i) {
      var o = t._gsap
      ;(o.scaleX = o.scaleY = r), o.renderTransform(i, o)
    },
    qr = function (t, e, r, n, i) {
      var o = t._gsap
      ;(o[e] = r), o.renderTransform(i, o)
    },
    Cr = 'transform',
    Lr = Cr + 'Origin',
    Mr = function (t, e) {
      var r = nr.createElementNS
        ? nr.createElementNS(
            (e || 'http://www.w3.org/1999/xhtml').replace(/^https/, 'http'),
            t
          )
        : nr.createElement(t)
      return r.style ? r : nr.createElement(t)
    },
    Pr = function t(e, r, n) {
      var i = getComputedStyle(e)
      return (
        i[r] ||
        i.getPropertyValue(r.replace(hr, '-$1').toLowerCase()) ||
        i.getPropertyValue(r) ||
        (!n && t(e, Dr(r) || r, 1)) ||
        ''
      )
    },
    Rr = 'O,Moz,ms,Ms,Webkit'.split(','),
    Dr = function (t, e, r) {
      var n = (e || sr).style,
        i = 5
      if (t in n && !r) return t
      for (
        t = t.charAt(0).toUpperCase() + t.substr(1);
        i-- && !(Rr[i] + t in n);

      );
      return i < 0 ? null : (3 === i ? 'ms' : i >= 0 ? Rr[i] : '') + t
    },
    Wr = function () {
      'undefined' != typeof window &&
        window.document &&
        ((rr = window),
        (nr = rr.document),
        (ir = nr.documentElement),
        (sr = Mr('div') || { style: {} }),
        (ar = Mr('div')),
        (Cr = Dr(Cr)),
        (Lr = Cr + 'Origin'),
        (sr.style.cssText =
          'border-width:0;line-height:0;position:absolute;padding:0'),
        (lr = !!Dr('perspective')),
        (or = 1))
    },
    Br = function t(e) {
      var r,
        n = Mr(
          'svg',
          (this.ownerSVGElement &&
            this.ownerSVGElement.getAttribute('xmlns')) ||
            'http://www.w3.org/2000/svg'
        ),
        i = this.parentNode,
        o = this.nextSibling,
        s = this.style.cssText
      if (
        (ir.appendChild(n),
        n.appendChild(this),
        (this.style.display = 'block'),
        e)
      )
        try {
          ;(r = this.getBBox()),
            (this._gsapBBox = this.getBBox),
            (this.getBBox = t)
        } catch (t) {}
      else this._gsapBBox && (r = this._gsapBBox())
      return (
        i && (o ? i.insertBefore(this, o) : i.appendChild(this)),
        ir.removeChild(n),
        (this.style.cssText = s),
        r
      )
    },
    Fr = function (t, e) {
      for (var r = e.length; r--; )
        if (t.hasAttribute(e[r])) return t.getAttribute(e[r])
    },
    Ir = function (t) {
      var e
      try {
        e = t.getBBox()
      } catch (r) {
        e = Br.call(t, !0)
      }
      return (
        (e && (e.width || e.height)) ||
          t.getBBox === Br ||
          (e = Br.call(t, !0)),
        !e || e.width || e.x || e.y
          ? e
          : {
              x: +Fr(t, ['x', 'cx', 'x1']) || 0,
              y: +Fr(t, ['y', 'cy', 'y1']) || 0,
              width: 0,
              height: 0,
            }
      )
    },
    zr = function (t) {
      return !(!t.getCTM || (t.parentNode && !t.ownerSVGElement) || !Ir(t))
    },
    Hr = function (t, e) {
      if (e) {
        var r = t.style
        e in cr && e !== Lr && (e = Cr),
          r.removeProperty
            ? (('ms' !== e.substr(0, 2) && 'webkit' !== e.substr(0, 6)) ||
                (e = '-' + e),
              r.removeProperty(e.replace(hr, '-$1').toLowerCase()))
            : r.removeAttribute(e)
      }
    },
    Nr = function (t, e, r, n, i, o) {
      var s = new $e(t._pt, e, r, 0, 1, o ? Tr : xr)
      return (t._pt = s), (s.b = n), (s.e = i), t._props.push(r), s
    },
    Yr = { deg: 1, rad: 1, turn: 1 },
    Vr = function t(e, r, n, i) {
      var o,
        s,
        a,
        u,
        l = parseFloat(n) || 0,
        c = (n + '').trim().substr((l + '').length) || 'px',
        d = sr.style,
        p = gr.test(r),
        f = 'svg' === e.tagName.toLowerCase(),
        h = (f ? 'client' : 'offset') + (p ? 'Width' : 'Height'),
        g = 100,
        m = 'px' === i,
        y = '%' === i
      return i === c || !l || Yr[i] || Yr[c]
        ? l
        : ('px' !== c && !m && (l = t(e, r, n, 'px')),
          (u = e.getCTM && zr(e)),
          (!y && '%' !== c) || (!cr[r] && !~r.indexOf('adius'))
            ? ((d[p ? 'width' : 'height'] = g + (m ? c : i)),
              (s =
                ~r.indexOf('adius') || ('em' === i && e.appendChild && !f)
                  ? e
                  : e.parentNode),
              u && (s = (e.ownerSVGElement || {}).parentNode),
              (s && s !== nr && s.appendChild) || (s = nr.body),
              (a = s._gsap) && y && a.width && p && a.time === fe.time
                ? at((l / a.width) * g)
                : ((y || '%' === c) && (d.position = Pr(e, 'position')),
                  s === e && (d.position = 'static'),
                  s.appendChild(sr),
                  (o = sr[h]),
                  s.removeChild(sr),
                  (d.position = 'absolute'),
                  p && y && (((a = it(s)).time = fe.time), (a.width = s[h])),
                  at(m ? (o * l) / g : o && l ? (g / o) * l : 0)))
            : ((o = u ? e.getBBox()[p ? 'width' : 'height'] : e[h]),
              at(y ? (l / o) * g : (l / 100) * o)))
    },
    Ur = function (t, e, r, n) {
      var i
      return (
        or || Wr(),
        e in yr &&
          'transform' !== e &&
          ~(e = yr[e]).indexOf(',') &&
          (e = e.split(',')[0]),
        cr[e] && 'transform' !== e
          ? ((i = rn(t, n)),
            (i =
              'transformOrigin' !== e
                ? i[e]
                : nn(Pr(t, Lr)) + ' ' + i.zOrigin + 'px'))
          : (!(i = t.style[e]) ||
              'auto' === i ||
              n ||
              ~(i + '').indexOf('calc(')) &&
            (i =
              (Zr[e] && Zr[e](t, e, r)) ||
              Pr(t, e) ||
              ot(t, e) ||
              ('opacity' === e ? 1 : 0)),
        r && !~(i + '').trim().indexOf(' ') ? Vr(t, e, i, r) + r : i
      )
    },
    Xr = function (t, e, r, n) {
      if (!r || 'none' === r) {
        var i = Dr(e, t, 1),
          o = i && Pr(t, i, 1)
        o && o !== r
          ? ((e = i), (r = o))
          : 'borderColor' === e && (r = Pr(t, 'borderTopColor'))
      }
      var s,
        a,
        u,
        l,
        c,
        d,
        p,
        f,
        h,
        m,
        y,
        _,
        v = new $e(this._pt, t.style, e, 0, 1, Ue),
        w = 0,
        b = 0
      if (
        ((v.b = r),
        (v.e = n),
        (r += ''),
        'auto' === (n += '') &&
          ((t.style[e] = n), (n = Pr(t, e) || n), (t.style[e] = r)),
        pe((s = [r, n])),
        (r = s[0]),
        (n = s[1]),
        (u = r.match(B) || []),
        (n.match(B) || []).length)
      ) {
        for (; (a = B.exec(n)); )
          (p = a[0]),
            (h = n.substring(w, a.index)),
            c
              ? (c = (c + 1) % 5)
              : ('rgba(' !== h.substr(-5) && 'hsla(' !== h.substr(-5)) ||
                (c = 1),
            p !== (d = u[b++] || '') &&
              ((l = parseFloat(d) || 0),
              (y = d.substr((l + '').length)),
              (_ = '=' === p.charAt(1) ? +(p.charAt(0) + '1') : 0) &&
                (p = p.substr(2)),
              (f = parseFloat(p)),
              (m = p.substr((f + '').length)),
              (w = B.lastIndex - m.length),
              m ||
                ((m = m || g.units[e] || y),
                w === n.length && ((n += m), (v.e += m))),
              y !== m && (l = Vr(t, e, d, m) || 0),
              (v._pt = {
                _next: v._pt,
                p: h || 1 === b ? h : ',',
                s: l,
                c: _ ? _ * f : f - l,
                m: (c && c < 4) || 'zIndex' === e ? Math.round : 0,
              }))
        v.c = w < n.length ? n.substring(w, n.length) : ''
      } else v.r = 'display' === e && 'none' === n ? Tr : xr
      return I.test(n) && (v.e = 0), (this._pt = v), v
    },
    jr = {
      top: '0%',
      bottom: '100%',
      left: '0%',
      right: '100%',
      center: '50%',
    },
    Gr = function (t, e) {
      if (e.tween && e.tween._time === e.tween._dur) {
        var r,
          n,
          i,
          o = e.t,
          s = o.style,
          a = e.u,
          u = o._gsap
        if ('all' === a || !0 === a) (s.cssText = ''), (n = 1)
        else
          for (i = (a = a.split(',')).length; --i > -1; )
            (r = a[i]),
              cr[r] && ((n = 1), (r = 'transformOrigin' === r ? Lr : Cr)),
              Hr(o, r)
        n &&
          (Hr(o, Cr),
          u &&
            (u.svg && o.removeAttribute('transform'),
            rn(o, 1),
            (u.uncache = 1)))
      }
    },
    Zr = {
      clearProps: function (t, e, r, n, i) {
        if ('isFromStart' !== i.data) {
          var o = (t._pt = new $e(t._pt, e, r, 0, 0, Gr))
          return (o.u = n), (o.pr = -10), (o.tween = i), t._props.push(r), 1
        }
      },
    },
    Qr = [1, 0, 0, 1, 0, 0],
    $r = {},
    Jr = function (t) {
      return 'matrix(1, 0, 0, 1, 0, 0)' === t || 'none' === t || !t
    },
    Kr = function (t) {
      var e = Pr(t, Cr)
      return Jr(e) ? Qr : e.substr(7).match(W).map(at)
    },
    tn = function (t, e) {
      var r,
        n,
        i,
        o,
        s = t._gsap || it(t),
        a = t.style,
        u = Kr(t)
      return s.svg && t.getAttribute('transform')
        ? '1,0,0,1,0,0' ===
          (u = [
            (i = t.transform.baseVal.consolidate().matrix).a,
            i.b,
            i.c,
            i.d,
            i.e,
            i.f,
          ]).join(',')
          ? Qr
          : u
        : (u !== Qr ||
            t.offsetParent ||
            t === ir ||
            s.svg ||
            ((i = a.display),
            (a.display = 'block'),
            ((r = t.parentNode) && t.offsetParent) ||
              ((o = 1), (n = t.nextSibling), ir.appendChild(t)),
            (u = Kr(t)),
            i ? (a.display = i) : Hr(t, 'display'),
            o &&
              (n
                ? r.insertBefore(t, n)
                : r
                ? r.appendChild(t)
                : ir.removeChild(t))),
          e && u.length > 6 ? [u[0], u[1], u[4], u[5], u[12], u[13]] : u)
    },
    en = function (t, e, r, n, i, o) {
      var s,
        a,
        u,
        l = t._gsap,
        c = i || tn(t, !0),
        d = l.xOrigin || 0,
        p = l.yOrigin || 0,
        f = l.xOffset || 0,
        h = l.yOffset || 0,
        g = c[0],
        m = c[1],
        y = c[2],
        _ = c[3],
        v = c[4],
        w = c[5],
        b = e.split(' '),
        x = parseFloat(b[0]) || 0,
        T = parseFloat(b[1]) || 0
      r
        ? c !== Qr &&
          (a = g * _ - m * y) &&
          ((u = x * (-m / a) + T * (g / a) - (g * w - m * v) / a),
          (x = x * (_ / a) + T * (-y / a) + (y * w - _ * v) / a),
          (T = u))
        : ((x = (s = Ir(t)).x + (~b[0].indexOf('%') ? (x / 100) * s.width : x)),
          (T =
            s.y + (~(b[1] || b[0]).indexOf('%') ? (T / 100) * s.height : T))),
        n || (!1 !== n && l.smooth)
          ? ((v = x - d),
            (w = T - p),
            (l.xOffset = f + (v * g + w * y) - v),
            (l.yOffset = h + (v * m + w * _) - w))
          : (l.xOffset = l.yOffset = 0),
        (l.xOrigin = x),
        (l.yOrigin = T),
        (l.smooth = !!n),
        (l.origin = e),
        (l.originIsAbsolute = !!r),
        (t.style[Lr] = '0px 0px'),
        o &&
          (Nr(o, l, 'xOrigin', d, x),
          Nr(o, l, 'yOrigin', p, T),
          Nr(o, l, 'xOffset', f, l.xOffset),
          Nr(o, l, 'yOffset', h, l.yOffset)),
        t.setAttribute('data-svg-origin', x + ' ' + T)
    },
    rn = function (t, e) {
      var r = t._gsap || new ke(t)
      if ('x' in r && !e && !r.uncache) return r
      var n,
        i,
        o,
        s,
        a,
        u,
        l,
        c,
        d,
        p,
        f,
        h,
        m,
        y,
        _,
        v,
        w,
        b,
        x,
        T,
        S,
        A,
        k,
        O,
        E,
        q,
        C,
        L,
        M,
        P,
        R,
        D,
        W = t.style,
        B = r.scaleX < 0,
        F = 'px',
        I = 'deg',
        z = Pr(t, Lr) || '0'
      return (
        (n = i = o = u = l = c = d = p = f = 0),
        (s = a = 1),
        (r.svg = !(!t.getCTM || !zr(t))),
        (y = tn(t, r.svg)),
        r.svg &&
          ((O = !r.uncache && t.getAttribute('data-svg-origin')),
          en(t, O || z, !!O || r.originIsAbsolute, !1 !== r.smooth, y)),
        (h = r.xOrigin || 0),
        (m = r.yOrigin || 0),
        y !== Qr &&
          ((b = y[0]),
          (x = y[1]),
          (T = y[2]),
          (S = y[3]),
          (n = A = y[4]),
          (i = k = y[5]),
          6 === y.length
            ? ((s = Math.sqrt(b * b + x * x)),
              (a = Math.sqrt(S * S + T * T)),
              (u = b || x ? fr(x, b) * dr : 0),
              (d = T || S ? fr(T, S) * dr + u : 0) && (a *= Math.cos(d * pr)),
              r.svg && ((n -= h - (h * b + m * T)), (i -= m - (h * x + m * S))))
            : ((D = y[6]),
              (P = y[7]),
              (C = y[8]),
              (L = y[9]),
              (M = y[10]),
              (R = y[11]),
              (n = y[12]),
              (i = y[13]),
              (o = y[14]),
              (l = (_ = fr(D, M)) * dr),
              _ &&
                ((O = A * (v = Math.cos(-_)) + C * (w = Math.sin(-_))),
                (E = k * v + L * w),
                (q = D * v + M * w),
                (C = A * -w + C * v),
                (L = k * -w + L * v),
                (M = D * -w + M * v),
                (R = P * -w + R * v),
                (A = O),
                (k = E),
                (D = q)),
              (c = (_ = fr(-T, M)) * dr),
              _ &&
                ((v = Math.cos(-_)),
                (R = S * (w = Math.sin(-_)) + R * v),
                (b = O = b * v - C * w),
                (x = E = x * v - L * w),
                (T = q = T * v - M * w)),
              (u = (_ = fr(x, b)) * dr),
              _ &&
                ((O = b * (v = Math.cos(_)) + x * (w = Math.sin(_))),
                (E = A * v + k * w),
                (x = x * v - b * w),
                (k = k * v - A * w),
                (b = O),
                (A = E)),
              l &&
                Math.abs(l) + Math.abs(u) > 359.9 &&
                ((l = u = 0), (c = 180 - c)),
              (s = at(Math.sqrt(b * b + x * x + T * T))),
              (a = at(Math.sqrt(k * k + D * D))),
              (_ = fr(A, k)),
              (d = Math.abs(_) > 2e-4 ? _ * dr : 0),
              (f = R ? 1 / (R < 0 ? -R : R) : 0)),
          r.svg &&
            ((O = t.getAttribute('transform')),
            (r.forceCSS = t.setAttribute('transform', '') || !Jr(Pr(t, Cr))),
            O && t.setAttribute('transform', O))),
        Math.abs(d) > 90 &&
          Math.abs(d) < 270 &&
          (B
            ? ((s *= -1),
              (d += u <= 0 ? 180 : -180),
              (u += u <= 0 ? 180 : -180))
            : ((a *= -1), (d += d <= 0 ? 180 : -180))),
        (r.x =
          n -
          ((r.xPercent =
            n &&
            (r.xPercent ||
              (Math.round(t.offsetWidth / 2) === Math.round(-n) ? -50 : 0)))
            ? (t.offsetWidth * r.xPercent) / 100
            : 0) +
          F),
        (r.y =
          i -
          ((r.yPercent =
            i &&
            (r.yPercent ||
              (Math.round(t.offsetHeight / 2) === Math.round(-i) ? -50 : 0)))
            ? (t.offsetHeight * r.yPercent) / 100
            : 0) +
          F),
        (r.z = o + F),
        (r.scaleX = at(s)),
        (r.scaleY = at(a)),
        (r.rotation = at(u) + I),
        (r.rotationX = at(l) + I),
        (r.rotationY = at(c) + I),
        (r.skewX = d + I),
        (r.skewY = p + I),
        (r.transformPerspective = f + F),
        (r.zOrigin = parseFloat(z.split(' ')[2]) || 0) && (W[Lr] = nn(z)),
        (r.xOffset = r.yOffset = 0),
        (r.force3D = g.force3D),
        (r.renderTransform = r.svg ? dn : lr ? cn : sn),
        (r.uncache = 0),
        r
      )
    },
    nn = function (t) {
      return (t = t.split(' '))[0] + ' ' + t[1]
    },
    on = function (t, e, r) {
      var n = Ht(e)
      return at(parseFloat(e) + parseFloat(Vr(t, 'x', r + 'px', n))) + n
    },
    sn = function (t, e) {
      ;(e.z = '0px'),
        (e.rotationY = e.rotationX = '0deg'),
        (e.force3D = 0),
        cn(t, e)
    },
    an = '0deg',
    un = '0px',
    ln = ') ',
    cn = function (t, e) {
      var r = e || this,
        n = r.xPercent,
        i = r.yPercent,
        o = r.x,
        s = r.y,
        a = r.z,
        u = r.rotation,
        l = r.rotationY,
        c = r.rotationX,
        d = r.skewX,
        p = r.skewY,
        f = r.scaleX,
        h = r.scaleY,
        g = r.transformPerspective,
        m = r.force3D,
        y = r.target,
        _ = r.zOrigin,
        v = '',
        w = ('auto' === m && t && 1 !== t) || !0 === m
      if (_ && (c !== an || l !== an)) {
        var b,
          x = parseFloat(l) * pr,
          T = Math.sin(x),
          S = Math.cos(x)
        ;(x = parseFloat(c) * pr),
          (b = Math.cos(x)),
          (o = on(y, o, T * b * -_)),
          (s = on(y, s, -Math.sin(x) * -_)),
          (a = on(y, a, S * b * -_ + _))
      }
      g !== un && (v += 'perspective(' + g + ln),
        (n || i) && (v += 'translate(' + n + '%, ' + i + '%) '),
        (w || o !== un || s !== un || a !== un) &&
          (v +=
            a !== un || w
              ? 'translate3d(' + o + ', ' + s + ', ' + a + ') '
              : 'translate(' + o + ', ' + s + ln),
        u !== an && (v += 'rotate(' + u + ln),
        l !== an && (v += 'rotateY(' + l + ln),
        c !== an && (v += 'rotateX(' + c + ln),
        (d === an && p === an) || (v += 'skew(' + d + ', ' + p + ln),
        (1 === f && 1 === h) || (v += 'scale(' + f + ', ' + h + ln),
        (y.style[Cr] = v || 'translate(0, 0)')
    },
    dn = function (t, e) {
      var r,
        n,
        i,
        o,
        s,
        a = e || this,
        u = a.xPercent,
        l = a.yPercent,
        c = a.x,
        d = a.y,
        p = a.rotation,
        f = a.skewX,
        h = a.skewY,
        g = a.scaleX,
        m = a.scaleY,
        y = a.target,
        _ = a.xOrigin,
        v = a.yOrigin,
        w = a.xOffset,
        b = a.yOffset,
        x = a.forceCSS,
        T = parseFloat(c),
        S = parseFloat(d)
      ;(p = parseFloat(p)),
        (f = parseFloat(f)),
        (h = parseFloat(h)) && ((f += h = parseFloat(h)), (p += h)),
        p || f
          ? ((p *= pr),
            (f *= pr),
            (r = Math.cos(p) * g),
            (n = Math.sin(p) * g),
            (i = Math.sin(p - f) * -m),
            (o = Math.cos(p - f) * m),
            f &&
              ((h *= pr),
              (s = Math.tan(f - h)),
              (i *= s = Math.sqrt(1 + s * s)),
              (o *= s),
              h &&
                ((s = Math.tan(h)), (r *= s = Math.sqrt(1 + s * s)), (n *= s))),
            (r = at(r)),
            (n = at(n)),
            (i = at(i)),
            (o = at(o)))
          : ((r = g), (o = m), (n = i = 0)),
        ((T && !~(c + '').indexOf('px')) || (S && !~(d + '').indexOf('px'))) &&
          ((T = Vr(y, 'x', c, 'px')), (S = Vr(y, 'y', d, 'px'))),
        (_ || v || w || b) &&
          ((T = at(T + _ - (_ * r + v * i) + w)),
          (S = at(S + v - (_ * n + v * o) + b))),
        (u || l) &&
          ((s = y.getBBox()),
          (T = at(T + (u / 100) * s.width)),
          (S = at(S + (l / 100) * s.height))),
        (s =
          'matrix(' +
          r +
          ',' +
          n +
          ',' +
          i +
          ',' +
          o +
          ',' +
          T +
          ',' +
          S +
          ')'),
        y.setAttribute('transform', s),
        x && (y.style[Cr] = s)
    },
    pn = function (t, e, r, n, i, o) {
      var s,
        a,
        u = 360,
        l = A(i),
        c = parseFloat(i) * (l && ~i.indexOf('rad') ? dr : 1),
        d = o ? c * o : c - n,
        p = n + d + 'deg'
      return (
        l &&
          ('short' === (s = i.split('_')[1]) &&
            (d %= u) !== d % 180 &&
            (d += d < 0 ? u : -360),
          'cw' === s && d < 0
            ? (d = ((d + 36e9) % u) - ~~(d / u) * u)
            : 'ccw' === s && d > 0 && (d = ((d - 36e9) % u) - ~~(d / u) * u)),
        (t._pt = a = new $e(t._pt, e, r, n, d, vr)),
        (a.e = p),
        (a.u = 'deg'),
        t._props.push(r),
        a
      )
    },
    fn = function (t, e, r) {
      var n,
        i,
        o,
        s,
        a,
        u,
        l,
        c = ar.style,
        d = r._gsap
      for (i in ((c.cssText =
        getComputedStyle(r).cssText + ';position:absolute;display:block;'),
      (c[Cr] = e),
      nr.body.appendChild(ar),
      (n = rn(ar, 1)),
      cr))
        (o = d[i]) !== (s = n[i]) &&
          'perspective,force3D,transformOrigin,svgOrigin'.indexOf(i) < 0 &&
          ((a = Ht(o) !== (l = Ht(s)) ? Vr(r, i, o, l) : parseFloat(o)),
          (u = parseFloat(s)),
          (t._pt = new $e(t._pt, d, i, a, u - a, _r)),
          (t._pt.u = l || 0),
          t._props.push(i))
      nr.body.removeChild(ar)
    }
  st('padding,margin,Width,Radius', function (t, e) {
    var r = 'Top',
      n = 'Right',
      i = 'Bottom',
      o = 'Left',
      s = (e < 3 ? [r, n, i, o] : [r + o, r + n, i + n, i + o]).map(function (
        r
      ) {
        return e < 2 ? t + r : 'border' + r + t
      })
    Zr[e > 1 ? 'border' + t : t] = function (t, e, r, n, i) {
      var o, a
      if (arguments.length < 4)
        return (
          (o = s.map(function (e) {
            return Ur(t, e, r)
          })),
          5 === (a = o.join(' ')).split(o[0]).length ? o[0] : a
        )
      ;(o = (n + '').split(' ')),
        (a = {}),
        s.forEach(function (t, e) {
          return (a[t] = o[e] = o[e] || o[((e - 1) / 2) | 0])
        }),
        t.init(e, a, i)
    }
  })
  var hn,
    gn,
    mn,
    yn = {
      name: 'css',
      register: Wr,
      targetTest: function (t) {
        return t.style && t.nodeType
      },
      init: function (t, e, r, n, i) {
        var o,
          s,
          a,
          u,
          l,
          c,
          d,
          p,
          f,
          h,
          m,
          y,
          _,
          v,
          w,
          b,
          x,
          T,
          S,
          A = this._props,
          k = t.style,
          O = r.vars.startAt
        for (d in (or || Wr(), e))
          if ('autoRound' !== d && ((s = e[d]), !J[d] || !Me(d, e, r, n, t, i)))
            if (
              ((l = typeof s),
              (c = Zr[d]),
              'function' === l && (l = typeof (s = s.call(r, n, t, i))),
              'string' === l && ~s.indexOf('random(') && (s = Jt(s)),
              c)
            )
              c(this, t, d, s, r) && (w = 1)
            else if ('--' === d.substr(0, 2))
              (o = (getComputedStyle(t).getPropertyValue(d) + '').trim()),
                (s += ''),
                (p = Ht(o)),
                (f = Ht(s))
                  ? p !== f && (o = Vr(t, d, o, f) + f)
                  : p && (s += p),
                this.add(k, 'setProperty', o, s, n, i, 0, 0, d)
            else if ('undefined' !== l) {
              if (
                (O && d in O
                  ? ((o =
                      'function' == typeof O[d] ? O[d].call(r, n, t, i) : O[d]),
                    d in g.units && !Ht(o) && (o += g.units[d]),
                    '=' === (o + '').charAt(1) && (o = Ur(t, d)))
                  : (o = Ur(t, d)),
                (u = parseFloat(o)),
                (h =
                  'string' === l && '=' === s.charAt(1)
                    ? +(s.charAt(0) + '1')
                    : 0) && (s = s.substr(2)),
                (a = parseFloat(s)),
                d in yr &&
                  ('autoAlpha' === d &&
                    (1 === u &&
                      'hidden' === Ur(t, 'visibility') &&
                      a &&
                      (u = 0),
                    Nr(
                      this,
                      k,
                      'visibility',
                      u ? 'inherit' : 'hidden',
                      a ? 'inherit' : 'hidden',
                      !a
                    )),
                  'scale' !== d &&
                    'transform' !== d &&
                    ~(d = yr[d]).indexOf(',') &&
                    (d = d.split(',')[0])),
                (m = d in cr))
              )
                if (
                  (y ||
                    (((_ = t._gsap).renderTransform && !e.parseTransform) ||
                      rn(t, e.parseTransform),
                    (v = !1 !== e.smoothOrigin && _.smooth),
                    ((y = this._pt =
                      new $e(
                        this._pt,
                        k,
                        Cr,
                        0,
                        1,
                        _.renderTransform,
                        _,
                        0,
                        -1
                      )).dep = 1)),
                  'scale' === d)
                )
                  (this._pt = new $e(
                    this._pt,
                    _,
                    'scaleY',
                    _.scaleY,
                    h ? h * a : a - _.scaleY
                  )),
                    A.push('scaleY', d),
                    (d += 'X')
                else {
                  if ('transformOrigin' === d) {
                    ;(x = void 0),
                      (T = void 0),
                      (S = void 0),
                      (x = (b = s).split(' ')),
                      (T = x[0]),
                      (S = x[1] || '50%'),
                      ('top' !== T &&
                        'bottom' !== T &&
                        'left' !== S &&
                        'right' !== S) ||
                        ((b = T), (T = S), (S = b)),
                      (x[0] = jr[T] || T),
                      (x[1] = jr[S] || S),
                      (s = x.join(' ')),
                      _.svg
                        ? en(t, s, 0, v, 0, this)
                        : ((f = parseFloat(s.split(' ')[2]) || 0) !==
                            _.zOrigin && Nr(this, _, 'zOrigin', _.zOrigin, f),
                          Nr(this, k, d, nn(o), nn(s)))
                    continue
                  }
                  if ('svgOrigin' === d) {
                    en(t, s, 1, v, 0, this)
                    continue
                  }
                  if (d in $r) {
                    pn(this, _, d, u, s, h)
                    continue
                  }
                  if ('smoothOrigin' === d) {
                    Nr(this, _, 'smooth', _.smooth, s)
                    continue
                  }
                  if ('force3D' === d) {
                    _[d] = s
                    continue
                  }
                  if ('transform' === d) {
                    fn(this, s, t)
                    continue
                  }
                }
              else d in k || (d = Dr(d) || d)
              if (
                m ||
                ((a || 0 === a) && (u || 0 === u) && !mr.test(s) && d in k)
              )
                a || (a = 0),
                  (p = (o + '').substr((u + '').length)) !==
                    (f = Ht(s) || (d in g.units ? g.units[d] : p)) &&
                    (u = Vr(t, d, o, f)),
                  (this._pt = new $e(
                    this._pt,
                    m ? _ : k,
                    d,
                    u,
                    h ? h * a : a - u,
                    m || ('px' !== f && 'zIndex' !== d) || !1 === e.autoRound
                      ? _r
                      : br
                  )),
                  (this._pt.u = f || 0),
                  p !== f && ((this._pt.b = o), (this._pt.r = wr))
              else if (d in k) Xr.call(this, t, d, o, s)
              else {
                if (!(d in t)) {
                  U(d, s)
                  continue
                }
                this.add(t, d, t[d], s, n, i)
              }
              A.push(d)
            }
        w && Qe(this)
      },
      get: Ur,
      aliases: yr,
      getSetter: function (t, e, r) {
        var n = yr[e]
        return (
          n && n.indexOf(',') < 0 && (e = n),
          e in cr && e !== Lr && (t._gsap.x || Ur(t, 'x'))
            ? r && ur === r
              ? 'scale' === e
                ? Or
                : kr
              : (ur = r || {}) && ('scale' === e ? Er : qr)
            : t.style && !E(t.style[e])
            ? Sr
            : ~e.indexOf('-')
            ? Ar
            : Ne(t, e)
        )
      },
      core: { _removeProperty: Hr, _getMatrix: tn },
    }
  ;(er.utils.checkPrefix = Dr),
    (mn = st(
      (hn = 'x,y,z,scale,scaleX,scaleY,xPercent,yPercent') +
        ',' +
        (gn = 'rotation,rotationX,rotationY,skewX,skewY') +
        ',transform,transformOrigin,svgOrigin,force3D,smoothOrigin,transformPerspective',
      function (t) {
        cr[t] = 1
      }
    )),
    st(gn, function (t) {
      ;(g.units[t] = 'deg'), ($r[t] = 1)
    }),
    (yr[mn[13]] = hn + ',' + gn),
    st(
      '0:translateX,1:translateY,2:translateZ,8:rotate,8:rotationZ,8:rotateZ,9:rotateX,10:rotateY',
      function (t) {
        var e = t.split(':')
        yr[e[1]] = mn[e[0]]
      }
    ),
    st(
      'x,y,z,top,right,bottom,left,width,height,fontSize,padding,margin,perspective',
      function (t) {
        g.units[t] = 'px'
      }
    ),
    er.registerPlugin(yn)
  var _n = er.registerPlugin(yn) || er
  function vn(t, e) {
    if (!(t instanceof e))
      throw new TypeError('Cannot call a class as a function')
  }
  function wn(t, e) {
    for (var r = 0; r < e.length; r++) {
      var n = e[r]
      ;(n.enumerable = n.enumerable || !1),
        (n.configurable = !0),
        'value' in n && (n.writable = !0),
        Object.defineProperty(t, n.key, n)
    }
  }
  function bn(t, e, r) {
    return e && wn(t.prototype, e), r && wn(t, r), t
  }
  /*!
   * ScrollTrigger 3.6.0
   * https://greensock.com
   *
   * @license Copyright 2008-2021, GreenSock. All rights reserved.
   * Subject to the terms at https://greensock.com/standard-license or for
   * Club GreenSock members, the agreement issued with that membership.
   * @author: Jack Doyle, jack@greensock.com
   */ _n.core.Tween
  var xn,
    Tn,
    Sn,
    An,
    kn,
    On,
    En,
    qn,
    Cn,
    Ln,
    Mn,
    Pn,
    Rn,
    Dn,
    Wn,
    Bn,
    Fn,
    In,
    zn,
    Hn,
    Nn,
    Yn,
    Vn,
    Un,
    Xn,
    jn,
    Gn,
    Zn = 1,
    Qn = [],
    $n = [],
    Jn = Date.now,
    Kn = Jn(),
    ti = 0,
    ei = 1,
    ri = function (t) {
      return t
    },
    ni = function () {
      return 'undefined' != typeof window
    },
    ii = function () {
      return xn || (ni() && (xn = window.gsap) && xn.registerPlugin && xn)
    },
    oi = function (t) {
      return !!~En.indexOf(t)
    },
    si = function (t, e) {
      return ~Qn.indexOf(t) && Qn[Qn.indexOf(t) + 1][e]
    },
    ai = function (t, e) {
      var r = e.s,
        n = e.sc,
        i = $n.indexOf(t),
        o = n === Wi.sc ? 1 : 2
      return (
        !~i && (i = $n.push(t) - 1),
        $n[i + o] ||
          ($n[i + o] =
            si(t, r) ||
            (oi(t)
              ? n
              : function (e) {
                  return arguments.length ? (t[r] = e) : t[r]
                }))
      )
    },
    ui = function (t) {
      return (
        si(t, 'getBoundingClientRect') ||
        (oi(t)
          ? function () {
              return (
                (xo.width = Sn.innerWidth), (xo.height = Sn.innerHeight), xo
              )
            }
          : function () {
              return Ii(t)
            })
      )
    },
    li = function (t, e) {
      var r = e.s,
        n = e.d2,
        i = e.d,
        o = e.a
      return (r = 'scroll' + n) && (o = si(t, r))
        ? o() - ui(t)()[i]
        : oi(t)
        ? Math.max(kn[r], On[r]) -
          (Sn['inner' + n] || kn['client' + n] || On['client' + n])
        : t[r] - t['offset' + n]
    },
    ci = function (t, e) {
      for (var r = 0; r < Nn.length; r += 3)
        (!e || ~e.indexOf(Nn[r + 1])) && t(Nn[r], Nn[r + 1], Nn[r + 2])
    },
    di = function (t) {
      return 'string' == typeof t
    },
    pi = function (t) {
      return 'function' == typeof t
    },
    fi = function (t) {
      return 'number' == typeof t
    },
    hi = function (t) {
      return 'object' == typeof t
    },
    gi = function (t) {
      return pi(t) && t()
    },
    mi = function (t, e) {
      return function () {
        var r = gi(t),
          n = gi(e)
        return function () {
          gi(r), gi(n)
        }
      }
    },
    yi = Math.abs,
    _i = 'scrollLeft',
    vi = 'scrollTop',
    wi = 'left',
    bi = 'top',
    xi = 'right',
    Ti = 'bottom',
    Si = 'width',
    Ai = 'height',
    ki = 'Right',
    Oi = 'Left',
    Ei = 'Top',
    qi = 'Bottom',
    Ci = 'padding',
    Li = 'margin',
    Mi = 'Width',
    Pi = 'Height',
    Ri = 'px',
    Di = {
      s: _i,
      p: wi,
      p2: Oi,
      os: xi,
      os2: ki,
      d: Si,
      d2: Mi,
      a: 'x',
      sc: function (t) {
        return arguments.length
          ? Sn.scrollTo(t, Wi.sc())
          : Sn.pageXOffset ||
              An.scrollLeft ||
              kn.scrollLeft ||
              On.scrollLeft ||
              0
      },
    },
    Wi = {
      s: vi,
      p: bi,
      p2: Ei,
      os: Ti,
      os2: qi,
      d: Ai,
      d2: Pi,
      a: 'y',
      op: Di,
      sc: function (t) {
        return arguments.length
          ? Sn.scrollTo(Di.sc(), t)
          : Sn.pageYOffset || An.scrollTop || kn.scrollTop || On.scrollTop || 0
      },
    },
    Bi = function (t) {
      return Sn.getComputedStyle(t)
    },
    Fi = function (t, e) {
      for (var r in e) r in t || (t[r] = e[r])
      return t
    },
    Ii = function (t, e) {
      var r =
          e &&
          'matrix(1, 0, 0, 1, 0, 0)' !== Bi(t)[Fn] &&
          xn
            .to(t, {
              x: 0,
              y: 0,
              xPercent: 0,
              yPercent: 0,
              rotation: 0,
              rotationX: 0,
              rotationY: 0,
              scale: 1,
              skewX: 0,
              skewY: 0,
            })
            .progress(1),
        n = t.getBoundingClientRect()
      return r && r.progress(0).kill(), n
    },
    zi = function (t, e) {
      var r = e.d2
      return t['offset' + r] || t['client' + r] || 0
    },
    Hi = function (t) {
      var e,
        r = [],
        n = t.labels,
        i = t.duration()
      for (e in n) r.push(n[e] / i)
      return r
    },
    Ni = function (t, e, r, n) {
      return r.split(',').forEach(function (r) {
        return t(e, r, n)
      })
    },
    Yi = function (t, e, r) {
      return t.addEventListener(e, r, { passive: !0 })
    },
    Vi = function (t, e, r) {
      return t.removeEventListener(e, r)
    },
    Ui = {
      startColor: 'green',
      endColor: 'red',
      indent: 0,
      fontSize: '16px',
      fontWeight: 'normal',
    },
    Xi = { toggleActions: 'play', anticipatePin: 0 },
    ji = { top: 0, left: 0, center: 0.5, bottom: 1, right: 1 },
    Gi = function (t, e) {
      if (di(t)) {
        var r = t.indexOf('='),
          n = ~r ? +(t.charAt(r - 1) + 1) * parseFloat(t.substr(r + 1)) : 0
        ~r && (t.indexOf('%') > r && (n *= e / 100), (t = t.substr(0, r - 1))),
          (t =
            n +
            (t in ji
              ? ji[t] * e
              : ~t.indexOf('%')
              ? (parseFloat(t) * e) / 100
              : parseFloat(t) || 0))
      }
      return t
    },
    Zi = function (t, e, r, n, i, o, s) {
      var a = i.startColor,
        u = i.endColor,
        l = i.fontSize,
        c = i.indent,
        d = i.fontWeight,
        p = An.createElement('div'),
        f = oi(r) || 'fixed' === si(r, 'pinType'),
        h = -1 !== t.indexOf('scroller'),
        g = f ? On : r,
        m = -1 !== t.indexOf('start'),
        y = m ? a : u,
        _ =
          'border-color:' +
          y +
          ';font-size:' +
          l +
          ';color:' +
          y +
          ';font-weight:' +
          d +
          ';pointer-events:none;white-space:nowrap;font-family:sans-serif,Arial;z-index:1000;padding:4px 8px;border-width:0;border-style:solid;'
      return (
        (_ += 'position:' + (h && f ? 'fixed;' : 'absolute;')),
        (h || !f) &&
          (_ += (n === Wi ? xi : Ti) + ':' + (o + parseFloat(c)) + 'px;'),
        s &&
          (_ +=
            'box-sizing:border-box;text-align:left;width:' +
            s.offsetWidth +
            'px;'),
        (p._isStart = m),
        p.setAttribute('class', 'gsap-marker-' + t),
        (p.style.cssText = _),
        (p.innerText = e || 0 === e ? t + '-' + e : t),
        g.children[0] ? g.insertBefore(p, g.children[0]) : g.appendChild(p),
        (p._offset = p['offset' + n.op.d2]),
        Qi(p, 0, n, m),
        p
      )
    },
    Qi = function (t, e, r, n) {
      var i = { display: 'block' },
        o = r[n ? 'os2' : 'p2'],
        s = r[n ? 'p2' : 'os2']
      ;(t._isFlipped = n),
        (i[r.a + 'Percent'] = n ? -100 : 0),
        (i[r.a] = n ? '1px' : 0),
        (i['border' + o + Mi] = 1),
        (i['border' + s + Mi] = 0),
        (i[r.p] = e + 'px'),
        xn.set(t, i)
    },
    $i = [],
    Ji = {},
    Ki = function () {
      return Ln || (Ln = Cn(go))
    },
    to = function () {
      Ln || ((Ln = Cn(go)), ti || ao('scrollStart'), (ti = Jn()))
    },
    eo = function () {
      return !Wn && !Un && !An.fullscreenElement && qn.restart(!0)
    },
    ro = {},
    no = [],
    io = [],
    oo = function (t) {
      var e,
        r = xn.ticker.frame,
        n = [],
        i = 0
      if (Gn !== r || Zn) {
        for (co(); i < io.length; i += 4)
          (e = Sn.matchMedia(io[i]).matches) !== io[i + 3] &&
            ((io[i + 3] = e),
            e ? n.push(i) : co(1, io[i]) || (pi(io[i + 2]) && io[i + 2]()))
        for (lo(), i = 0; i < n.length; i++)
          (e = n[i]), (jn = io[e]), (io[e + 2] = io[e + 1](t))
        ;(jn = 0), Tn && po(0, 1), (Gn = r), ao('matchMedia')
      }
    },
    so = function t() {
      return Vi(Oo, 'scrollEnd', t) || po(!0)
    },
    ao = function (t) {
      return (
        (ro[t] &&
          ro[t].map(function (t) {
            return t()
          })) ||
        no
      )
    },
    uo = [],
    lo = function (t) {
      for (var e = 0; e < uo.length; e += 4)
        (t && uo[e + 3] !== t) ||
          ((uo[e].style.cssText = uo[e + 1]), (uo[e + 2].uncache = 1))
    },
    co = function (t, e) {
      var r
      for (In = 0; In < $i.length; In++)
        (r = $i[In]),
          (e && r.media !== e) ||
            (t
              ? r.kill(1)
              : (r.scroll.rec || (r.scroll.rec = r.scroll()), r.revert()))
      lo(e), e || ao('revert')
    },
    po = function (t, e) {
      if (!ti || t) {
        var r = ao('refreshInit')
        for (Yn && Oo.sort(), e || co(), In = 0; In < $i.length; In++)
          $i[In].refresh()
        for (
          r.forEach(function (t) {
            return t && t.render && t.render(-1)
          }),
            In = $i.length;
          In--;

        )
          $i[In].scroll.rec = 0
        qn.pause(), ao('refresh')
      } else Yi(Oo, 'scrollEnd', so)
    },
    fo = 0,
    ho = 1,
    go = function () {
      var t = $i.length,
        e = Jn(),
        r = e - Kn >= 50,
        n = t && $i[0].scroll()
      if (
        ((ho = fo > n ? -1 : 1),
        (fo = n),
        r &&
          (ti && !Bn && e - ti > 200 && ((ti = 0), ao('scrollEnd')),
          (Rn = Kn),
          (Kn = e)),
        ho < 0)
      ) {
        for (In = t; In--; ) $i[In] && $i[In].update(0, r)
        ho = 1
      } else for (In = 0; In < t; In++) $i[In] && $i[In].update(0, r)
      Ln = 0
    },
    mo = [
      wi,
      bi,
      Ti,
      xi,
      'marginBottom',
      'marginRight',
      'marginTop',
      'marginLeft',
      'display',
      'flexShrink',
      'float',
      'zIndex',
    ],
    yo = mo.concat([
      Si,
      Ai,
      'boxSizing',
      'maxWidth',
      'maxHeight',
      'position',
      Li,
      Ci,
      'paddingTop',
      'paddingRight',
      'paddingBottom',
      'paddingLeft',
    ]),
    _o = function (t, e, r, n) {
      if (t.parentNode !== e) {
        for (var i, o = mo.length, s = e.style, a = t.style; o--; )
          s[(i = mo[o])] = r[i]
        ;(s.position = 'absolute' === r.position ? 'absolute' : 'relative'),
          'inline' === r.display && (s.display = 'inline-block'),
          (a.bottom = a.right = 'auto'),
          (s.overflow = 'visible'),
          (s.boxSizing = 'border-box'),
          (s.width = zi(t, Di) + Ri),
          (s.height = zi(t, Wi) + Ri),
          (s.padding = a.margin = a.top = a.left = '0'),
          wo(n),
          (a.width = a.maxWidth = r.width),
          (a.height = a.maxHeight = r.height),
          (a.padding = r.padding),
          t.parentNode.insertBefore(e, t),
          e.appendChild(t)
      }
    },
    vo = /([A-Z])/g,
    wo = function (t) {
      if (t) {
        var e,
          r,
          n = t.t.style,
          i = t.length,
          o = 0
        for ((t.t._gsap || xn.core.getCache(t.t)).uncache = 1; o < i; o += 2)
          (r = t[o + 1]),
            (e = t[o]),
            r
              ? (n[e] = r)
              : n[e] && n.removeProperty(e.replace(vo, '-$1').toLowerCase())
      }
    },
    bo = function (t) {
      for (var e = yo.length, r = t.style, n = [], i = 0; i < e; i++)
        n.push(yo[i], r[yo[i]])
      return (n.t = t), n
    },
    xo = { left: 0, top: 0 },
    To = function (t, e, r, n, i, o, s, a, u, l, c, d) {
      if (
        (pi(t) && (t = t(a)),
        di(t) &&
          'max' === t.substr(0, 3) &&
          (t = d + ('=' === t.charAt(4) ? Gi('0' + t.substr(3), r) : 0)),
        fi(t))
      )
        s && Qi(s, r, n, !0)
      else {
        pi(e) && (e = e(a))
        var p,
          f,
          h,
          g = Mn(e)[0] || On,
          m = Ii(g) || {},
          y = t.split(' ')
        ;(m && (m.left || m.top)) ||
          'none' !== Bi(g).display ||
          ((h = g.style.display),
          (g.style.display = 'block'),
          (m = Ii(g)),
          h ? (g.style.display = h) : g.style.removeProperty('display')),
          (p = Gi(y[0], m[n.d])),
          (f = Gi(y[1] || '0', r)),
          (t = m[n.p] - u[n.p] - l + p + i - f),
          s && Qi(s, f, n, r - f < 20 || (s._isStart && f > 20)),
          (r -= r - f)
      }
      if (o) {
        var _ = t + r,
          v = o._isStart
        ;(d = 'scroll' + n.d2),
          Qi(
            o,
            _,
            n,
            (v && _ > 20) ||
              (!v && (c ? Math.max(On[d], kn[d]) : o.parentNode[d]) <= _ + 1)
          ),
          c &&
            ((u = Ii(s)),
            c && (o.style[n.op.p] = u[n.op.p] - n.op.m - o._offset + Ri))
      }
      return Math.round(t)
    },
    So = /(?:webkit|moz|length|cssText|inset)/i,
    Ao = function (t, e, r, n) {
      if (t.parentNode !== e) {
        var i,
          o,
          s = t.style
        if (e === On) {
          for (i in ((t._stOrig = s.cssText), (o = Bi(t))))
            +i ||
              So.test(i) ||
              !o[i] ||
              'string' != typeof s[i] ||
              '0' === i ||
              (s[i] = o[i])
          ;(s.top = r), (s.left = n)
        } else s.cssText = t._stOrig
        ;(xn.core.getCache(t).uncache = 1), e.appendChild(t)
      }
    },
    ko = function (t, e) {
      var r,
        n,
        i = ai(t, e),
        o = '_scroll' + e.p2,
        s = function e(s, a, u, l, c) {
          var d = e.tween,
            p = a.onComplete,
            f = {}
          return (
            d && d.kill(),
            (r = Math.round(u)),
            (a[o] = s),
            (a.modifiers = f),
            (f[o] = function (t) {
              return (
                (t = Math.round(i())) !== r && t !== n && Math.abs(t - r) > 2
                  ? (d.kill(), (e.tween = 0))
                  : (t = u + l * d.ratio + c * d.ratio * d.ratio),
                (n = r),
                (r = Math.round(t))
              )
            }),
            (a.onComplete = function () {
              ;(e.tween = 0), p && p.call(d)
            }),
            (d = e.tween = xn.to(t, a))
          )
        }
      return (
        (t[o] = i),
        t.addEventListener('mousewheel', function () {
          return s.tween && s.tween.kill() && (s.tween = 0)
        }),
        s
      )
    }
  Di.op = Wi
  var Oo = (function () {
    function t(e, r) {
      Tn ||
        t.register(xn) ||
        console.warn('Please gsap.registerPlugin(ScrollTrigger)'),
        this.init(e, r)
    }
    return (
      (t.prototype.init = function (e, r) {
        if (((this.progress = this.start = 0), this.vars && this.kill(1), ei)) {
          var n,
            i,
            o,
            s,
            a,
            u,
            l,
            c,
            d,
            p,
            f,
            h,
            g,
            m,
            y,
            _,
            v,
            w,
            b,
            x,
            T,
            S,
            A,
            k,
            O,
            E,
            q,
            C,
            L,
            M,
            P,
            R,
            D,
            W,
            B,
            F,
            I,
            z,
            H,
            N = (e = Fi(di(e) || fi(e) || e.nodeType ? { trigger: e } : e, Xi))
              .horizontal
              ? Di
              : Wi,
            Y = e,
            V = Y.onUpdate,
            U = Y.toggleClass,
            X = Y.id,
            j = Y.onToggle,
            G = Y.onRefresh,
            Z = Y.scrub,
            Q = Y.trigger,
            $ = Y.pin,
            J = Y.pinSpacing,
            K = Y.invalidateOnRefresh,
            tt = Y.anticipatePin,
            et = Y.onScrubComplete,
            rt = Y.onSnapComplete,
            nt = Y.once,
            it = Y.snap,
            ot = Y.pinReparent,
            st = !Z && 0 !== Z,
            at = Mn(e.scroller || Sn)[0],
            ut = xn.core.getCache(at),
            lt = oi(at),
            ct =
              'pinType' in e
                ? 'fixed' === e.pinType
                : lt || 'fixed' === si(at, 'pinType'),
            dt = [e.onEnter, e.onLeave, e.onEnterBack, e.onLeaveBack],
            pt = st && e.toggleActions.split(' '),
            ft = 'markers' in e ? e.markers : Xi.markers,
            ht = lt ? 0 : parseFloat(Bi(at)['border' + N.p2 + Mi]) || 0,
            gt = this,
            mt =
              e.onRefreshInit &&
              function () {
                return e.onRefreshInit(gt)
              },
            yt = (function (t, e, r) {
              var n = r.d,
                i = r.d2,
                o = r.a
              return (o = si(t, 'getBoundingClientRect'))
                ? function () {
                    return o()[n]
                  }
                : function () {
                    return (e ? Sn['inner' + i] : t['client' + i]) || 0
                  }
            })(at, lt, N),
            _t = (function (t, e) {
              return !e || ~Qn.indexOf(t)
                ? ui(t)
                : function () {
                    return xo
                  }
            })(at, lt)
          ;(gt.media = jn),
            (tt *= 45),
            $i.push(gt),
            (gt.scroller = at),
            (gt.scroll = ai(at, N)),
            (a = gt.scroll()),
            (gt.vars = e),
            (r = r || e.animation),
            'refreshPriority' in e && (Yn = 1),
            (ut.tweenScroll = ut.tweenScroll || {
              top: ko(at, Wi),
              left: ko(at, Di),
            }),
            (gt.tweenTo = n = ut.tweenScroll[N.p]),
            r &&
              ((r.vars.lazy = !1),
              r._initted ||
                (!1 !== r.vars.immediateRender &&
                  !1 !== e.immediateRender &&
                  r.render(0, !0, !0)),
              (gt.animation = r.pause()),
              (r.scrollTrigger = gt),
              (R = fi(Z) && Z) &&
                (P = xn.to(r, {
                  ease: 'power3',
                  duration: R,
                  onComplete: function () {
                    return et && et(gt)
                  },
                })),
              (L = 0),
              X || (X = r.vars.id)),
            it &&
              (hi(it) || (it = { snapTo: it }),
              'scrollBehavior' in On.style &&
                xn.set(lt ? [On, kn] : at, { scrollBehavior: 'auto' }),
              (o = pi(it.snapTo)
                ? it.snapTo
                : 'labels' === it.snapTo
                ? (function (t) {
                    return function (e) {
                      return xn.utils.snap(Hi(t), e)
                    }
                  })(r)
                : 'labelsDirectional' === it.snapTo
                ? ((z = r),
                  function (t, e) {
                    var r,
                      n = Hi(z)
                    if (
                      (n.sort(function (t, e) {
                        return t - e
                      }),
                      e.direction > 0)
                    ) {
                      for (r = 0; r < n.length; r++) if (n[r] >= t) return n[r]
                      return n.pop()
                    }
                    for (r = n.length; r--; ) if (n[r] <= t) return n[r]
                    return n[0]
                  })
                : xn.utils.snap(it.snapTo)),
              (D = it.duration || { min: 0.1, max: 2 }),
              (D = hi(D) ? Pn(D.min, D.max) : Pn(D, D)),
              (W = xn
                .delayedCall(it.delay || R / 2 || 0.1, function () {
                  if (Math.abs(gt.getVelocity()) < 10 && !Bn) {
                    var t = r && !st ? r.totalProgress() : gt.progress,
                      e = ((t - M) / (Jn() - Rn)) * 1e3 || 0,
                      i = (yi(e / 2) * e) / 0.185,
                      s = t + i,
                      a = Pn(0, 1, o(s, gt)),
                      u = gt.scroll(),
                      d = Math.round(l + a * m),
                      p = n.tween
                    if (u <= c && u >= l && d !== u) {
                      if (p && !p._initted && p.data <= Math.abs(d - u)) return
                      n(
                        d,
                        {
                          duration: D(
                            yi(
                              (0.185 * Math.max(yi(s - t), yi(a - t))) /
                                e /
                                0.05 || 0
                            )
                          ),
                          ease: it.ease || 'power3',
                          data: Math.abs(d - u),
                          onComplete: function () {
                            ;(L = M =
                              r && !st ? r.totalProgress() : gt.progress),
                              rt && rt(gt)
                          },
                        },
                        u,
                        i * m,
                        d - u - i * m
                      )
                    }
                  } else gt.isActive && W.restart(!0)
                })
                .pause())),
            X && (Ji[X] = gt),
            (Q = gt.trigger = Mn(Q || $)[0]),
            ($ = !0 === $ ? Q : Mn($)[0]),
            di(U) && (U = { targets: Q, className: U }),
            $ &&
              (!1 === J ||
                J === Li ||
                (J = !(!J && 'flex' === Bi($.parentNode).display) && Ci),
              (gt.pin = $),
              !1 !== e.force3D && xn.set($, { force3D: !0 }),
              (i = xn.core.getCache($)).spacer
                ? (y = i.pinState)
                : ((i.spacer = w = An.createElement('div')),
                  w.setAttribute(
                    'class',
                    'pin-spacer' + (X ? ' pin-spacer-' + X : '')
                  ),
                  (i.pinState = y = bo($))),
              (gt.spacer = w = i.spacer),
              (C = Bi($)),
              (k = C[J + N.os2]),
              (x = xn.getProperty($)),
              (T = xn.quickSetter($, N.a, Ri)),
              _o($, w, C),
              (v = bo($))),
            ft &&
              ((g = hi(ft) ? Fi(ft, Ui) : Ui),
              (f = Zi('scroller-start', X, at, N, g, 0)),
              (h = Zi('scroller-end', X, at, N, g, 0, f)),
              (b = f['offset' + N.op.d2]),
              (d = Zi('start', X, at, N, g, b)),
              (p = Zi('end', X, at, N, g, b)),
              ct ||
                (((H = lt ? On : at).style.position =
                  'absolute' === Bi(H).position ? 'absolute' : 'relative'),
                xn.set([f, h], { force3D: !0 }),
                (E = xn.quickSetter(f, N.a, Ri)),
                (q = xn.quickSetter(h, N.a, Ri)))),
            (gt.revert = function (t) {
              var e = !1 !== t || !gt.enabled,
                n = Wn
              e !== s &&
                (e &&
                  ((F = Math.max(gt.scroll(), gt.scroll.rec || 0)),
                  (B = gt.progress),
                  (I = r && r.progress())),
                d &&
                  [d, p, f, h].forEach(function (t) {
                    return (t.style.display = e ? 'none' : 'block')
                  }),
                e && (Wn = 1),
                gt.update(e),
                (Wn = n),
                $ &&
                  (e
                    ? (function (t, e, r) {
                        if ((wo(r), t.parentNode === e)) {
                          var n = e.parentNode
                          n && (n.insertBefore(t, e), n.removeChild(e))
                        }
                      })($, w, y)
                    : (!ot || !gt.isActive) && _o($, w, Bi($), O)),
                (s = e))
            }),
            (gt.refresh = function (n) {
              if (!Wn && gt.enabled)
                if ($ && n && ti) Yi(t, 'scrollEnd', so)
                else {
                  ;(Wn = 1),
                    P && P.pause(),
                    K && r && r.progress(0).invalidate(),
                    s || gt.revert()
                  for (
                    var i,
                      o,
                      g,
                      b,
                      T,
                      k,
                      E,
                      q,
                      C,
                      L = yt(),
                      M = _t(),
                      R = li(at, N),
                      D = 0,
                      W = 0,
                      z = e.end,
                      H = e.endTrigger || Q,
                      Y =
                        e.start ||
                        (0 !== e.start && Q ? ($ ? '0 0' : '0 100%') : 0),
                      V = (Q && Math.max(0, $i.indexOf(gt))) || 0,
                      U = V;
                    U--;

                  )
                    (E = $i[U].pin) && (E === Q || E === $) && $i[U].revert()
                  for (
                    l =
                      To(Y, Q, L, N, gt.scroll(), d, f, gt, M, ht, ct, R) ||
                      ($ ? -0.001 : 0),
                      pi(z) && (z = z(gt)),
                      di(z) &&
                        !z.indexOf('+=') &&
                        (~z.indexOf(' ')
                          ? (z = (di(Y) ? Y.split(' ')[0] : '') + z)
                          : ((D = Gi(z.substr(2), L)),
                            (z = di(Y) ? Y : l + D),
                            (H = Q))),
                      c =
                        Math.max(
                          l,
                          To(
                            z || (H ? '100% 0' : R),
                            H,
                            L,
                            N,
                            gt.scroll() + D,
                            p,
                            h,
                            gt,
                            M,
                            ht,
                            ct,
                            R
                          )
                        ) || -0.001,
                      m = c - l || ((l -= 0.01) && 0.001),
                      D = 0,
                      U = V;
                    U--;

                  )
                    (E = (k = $i[U]).pin) &&
                      k.start - k._pinPush < l &&
                      ((i = k.end - k.start),
                      E === Q && (D += i),
                      E === $ && (W += i))
                  if (
                    ((l += D),
                    (c += D),
                    (gt._pinPush = W),
                    d && D && (((i = {})[N.a] = '+=' + D), xn.set([d, p], i)),
                    $)
                  )
                    (i = Bi($)),
                      (b = N === Wi),
                      (g = gt.scroll()),
                      (S = parseFloat(x(N.a)) + W),
                      !R &&
                        c > 1 &&
                        ((lt ? On : at).style['overflow-' + N.a] = 'scroll'),
                      _o($, w, i),
                      (v = bo($)),
                      (o = Ii($, !0)),
                      (q = ct && ai(at, b ? Di : Wi)()),
                      J &&
                        (((O = [J + N.os2, m + W + Ri]).t = w),
                        (U = J === Ci ? zi($, N) + m + W : 0) &&
                          O.push(N.d, U + Ri),
                        wo(O),
                        ct && gt.scroll(F)),
                      ct &&
                        (((T = {
                          top: o.top + (b ? g - l : q) + Ri,
                          left: o.left + (b ? q : g - l) + Ri,
                          boxSizing: 'border-box',
                          position: 'fixed',
                        }).width = T.maxWidth =
                          Math.ceil(o.width) + Ri),
                        (T.height = T.maxHeight = Math.ceil(o.height) + Ri),
                        (T.margin =
                          T.marginTop =
                          T.marginRight =
                          T.marginBottom =
                          T.marginLeft =
                            '0'),
                        (T.padding = i.padding),
                        (T.paddingTop = i.paddingTop),
                        (T.paddingRight = i.paddingRight),
                        (T.paddingBottom = i.paddingBottom),
                        (T.paddingLeft = i.paddingLeft),
                        (_ = (function (t, e, r) {
                          for (
                            var n, i = [], o = t.length, s = r ? 8 : 0;
                            s < o;
                            s += 2
                          )
                            (n = t[s]), i.push(n, n in e ? e[n] : t[s + 1])
                          return (i.t = t.t), i
                        })(y, T, ot))),
                      r
                        ? ((C = r._initted),
                          Vn(1),
                          r.progress(1, !0),
                          (A = x(N.a) - S + m + W),
                          m !== A && _.splice(_.length - 2, 2),
                          r.progress(0, !0),
                          C || r.invalidate(),
                          Vn(0))
                        : (A = m)
                  else if (Q && gt.scroll())
                    for (o = Q.parentNode; o && o !== On; )
                      o._pinOffset &&
                        ((l -= o._pinOffset), (c -= o._pinOffset)),
                        (o = o.parentNode)
                  for (U = 0; U < V; U++)
                    (k = $i[U].pin) && (k === Q || k === $) && $i[U].revert(!1)
                  ;(gt.start = l),
                    (gt.end = c),
                    (a = u = gt.scroll()) < F && gt.scroll(F),
                    gt.revert(!1),
                    (Wn = 0),
                    r &&
                      st &&
                      r._initted &&
                      r.progress(I, !0).render(r.time(), !0, !0),
                    B !== gt.progress &&
                      (P && r.totalProgress(B, !0),
                      (gt.progress = B),
                      gt.update()),
                    $ && J && (w._pinOffset = Math.round(gt.progress * A)),
                    G && G(gt)
                }
            }),
            (gt.getVelocity = function () {
              return ((gt.scroll() - u) / (Jn() - Rn)) * 1e3 || 0
            }),
            (gt.update = function (t, e) {
              var i,
                o,
                s,
                d,
                p,
                h = gt.scroll(),
                g = t ? 0 : (h - l) / m,
                y = g < 0 ? 0 : g > 1 ? 1 : g || 0,
                b = gt.progress
              if (
                (e &&
                  ((u = a),
                  (a = h),
                  it && ((M = L), (L = r && !st ? r.totalProgress() : y))),
                tt &&
                  !y &&
                  $ &&
                  !Wn &&
                  !Zn &&
                  ti &&
                  l < h + ((h - u) / (Jn() - Rn)) * tt &&
                  (y = 1e-4),
                y !== b && gt.enabled)
              ) {
                if (
                  ((d =
                    (p = (i = gt.isActive = !!y && y < 1) !== (!!b && b < 1)) ||
                    !!y != !!b),
                  (gt.direction = y > b ? 1 : -1),
                  (gt.progress = y),
                  st ||
                    (!P || Wn || Zn
                      ? r && r.totalProgress(y, !!Wn)
                      : ((P.vars.totalProgress = y), P.invalidate().restart())),
                  $)
                )
                  if ((t && J && (w.style[J + N.os2] = k), ct)) {
                    if (d) {
                      if (
                        ((s = !t && y > b && c + 1 > h && h + 1 >= li(at, N)),
                        ot)
                      )
                        if (t || (!i && !s)) Ao($, w)
                        else {
                          var x = Ii($, !0),
                            O = h - l
                          Ao(
                            $,
                            On,
                            x.top + (N === Wi ? O : 0) + Ri,
                            x.left + (N === Wi ? 0 : O) + Ri
                          )
                        }
                      wo(i || s ? _ : v),
                        (A !== m && y < 1 && i) || T(S + (1 !== y || s ? 0 : A))
                    }
                  } else T(S + A * y)
                it && !n.tween && !Wn && !Zn && W.restart(!0),
                  U &&
                    (p || (nt && y && (y < 1 || !Xn))) &&
                    Mn(U.targets).forEach(function (t) {
                      return t.classList[i || nt ? 'add' : 'remove'](
                        U.className
                      )
                    }),
                  V && !st && !t && V(gt),
                  d && !Wn
                    ? ((o = y && !b ? 0 : 1 === y ? 1 : 1 === b ? 2 : 3),
                      st &&
                        ((s =
                          (!p && 'none' !== pt[o + 1] && pt[o + 1]) || pt[o]),
                        r &&
                          ('complete' === s || 'reset' === s || s in r) &&
                          ('complete' === s
                            ? r.pause().totalProgress(1)
                            : 'reset' === s
                            ? r.restart(!0).pause()
                            : r[s]()),
                        V && V(gt)),
                      (!p && Xn) ||
                        (j && p && j(gt),
                        dt[o] && dt[o](gt),
                        nt && (1 === y ? gt.kill(!1, 1) : (dt[o] = 0)),
                        p || (dt[(o = 1 === y ? 1 : 3)] && dt[o](gt))))
                    : st && V && !Wn && V(gt)
              }
              q && (E(h + (f._isFlipped ? 1 : 0)), q(h))
            }),
            (gt.enable = function () {
              gt.enabled ||
                ((gt.enabled = !0),
                Yi(at, 'resize', eo),
                Yi(at, 'scroll', to),
                mt && Yi(t, 'refreshInit', mt),
                r && r.add
                  ? xn.delayedCall(0.01, function () {
                      return l || c || gt.refresh()
                    }) &&
                    (m = 0.01) &&
                    (l = c = 0)
                  : gt.refresh())
            }),
            (gt.disable = function (e, r) {
              if (
                gt.enabled &&
                (!1 !== e && gt.revert(),
                (gt.enabled = gt.isActive = !1),
                r || (P && P.pause()),
                (F = 0),
                i && (i.uncache = 1),
                mt && Vi(t, 'refreshInit', mt),
                W && (W.pause(), n.tween && n.tween.kill() && (n.tween = 0)),
                !lt)
              ) {
                for (var o = $i.length; o--; )
                  if ($i[o].scroller === at && $i[o] !== gt) return
                Vi(at, 'resize', eo), Vi(at, 'scroll', to)
              }
            }),
            (gt.kill = function (t, e) {
              gt.disable(t, e), X && delete Ji[X]
              var n = $i.indexOf(gt)
              $i.splice(n, 1),
                n === In && ho > 0 && In--,
                r &&
                  ((r.scrollTrigger = null), t && r.render(-1), e || r.kill()),
                d &&
                  [d, p, f, h].forEach(function (t) {
                    return t.parentNode.removeChild(t)
                  }),
                $ &&
                  (i && (i.uncache = 1),
                  (n = 0),
                  $i.forEach(function (t) {
                    return t.pin === $ && n++
                  }),
                  n || (i.spacer = 0))
            }),
            gt.enable()
        } else this.update = this.refresh = this.kill = ri
      }),
      (t.register = function (e) {
        if (
          !Tn &&
          ((xn = e || ii()),
          ni() &&
            window.document &&
            ((Sn = window),
            (An = document),
            (kn = An.documentElement),
            (On = An.body)),
          xn &&
            ((Mn = xn.utils.toArray),
            (Pn = xn.utils.clamp),
            (Vn = xn.core.suppressOverwrites || ri),
            xn.core.globals('ScrollTrigger', t),
            On))
        ) {
          ;(Cn =
            Sn.requestAnimationFrame ||
            function (t) {
              return setTimeout(t, 16)
            }),
            Yi(Sn, 'mousewheel', to),
            (En = [Sn, An, kn, On]),
            Yi(An, 'scroll', to)
          var r,
            n = On.style,
            i = n.borderTop
          ;(n.borderTop = '1px solid #000'),
            (r = Ii(On)),
            (Wi.m = Math.round(r.top + Wi.sc()) || 0),
            (Di.m = Math.round(r.left + Di.sc()) || 0),
            i ? (n.borderTop = i) : n.removeProperty('border-top'),
            (Dn = setInterval(Ki, 200)),
            xn.delayedCall(0.5, function () {
              return (Zn = 0)
            }),
            Yi(An, 'touchcancel', ri),
            Yi(On, 'touchstart', ri),
            Ni(Yi, An, 'pointerdown,touchstart,mousedown', function () {
              return (Bn = 1)
            }),
            Ni(Yi, An, 'pointerup,touchend,mouseup', function () {
              return (Bn = 0)
            }),
            (Fn = xn.utils.checkPrefix('transform')),
            yo.push(Fn),
            (Tn = Jn()),
            (qn = xn.delayedCall(0.2, po).pause()),
            (Nn = [
              An,
              'visibilitychange',
              function () {
                var t = Sn.innerWidth,
                  e = Sn.innerHeight
                An.hidden
                  ? ((zn = t), (Hn = e))
                  : (zn === t && Hn === e) || eo()
              },
              An,
              'DOMContentLoaded',
              po,
              Sn,
              'load',
              function () {
                return ti || po()
              },
              Sn,
              'resize',
              eo,
            ]),
            ci(Yi)
        }
        return Tn
      }),
      (t.defaults = function (t) {
        for (var e in t) Xi[e] = t[e]
      }),
      (t.kill = function () {
        ;(ei = 0),
          $i.slice(0).forEach(function (t) {
            return t.kill(1)
          })
      }),
      (t.config = function (t) {
        'limitCallbacks' in t && (Xn = !!t.limitCallbacks)
        var e = t.syncInterval
        ;(e && clearInterval(Dn)) || ((Dn = e) && setInterval(Ki, e)),
          'autoRefreshEvents' in t &&
            (ci(Vi) || ci(Yi, t.autoRefreshEvents || 'none'),
            (Un = -1 === (t.autoRefreshEvents + '').indexOf('resize')))
      }),
      (t.scrollerProxy = function (t, e) {
        var r = Mn(t)[0],
          n = $n.indexOf(r),
          i = oi(r)
        ~n && $n.splice(n, i ? 6 : 2),
          i ? Qn.unshift(Sn, e, On, e, kn, e) : Qn.unshift(r, e)
      }),
      (t.matchMedia = function (t) {
        var e, r, n, i, o
        for (r in t)
          (n = io.indexOf(r)),
            (i = t[r]),
            (jn = r),
            'all' === r
              ? i()
              : (e = Sn.matchMedia(r)) &&
                (e.matches && (o = i()),
                ~n
                  ? ((io[n + 1] = mi(io[n + 1], i)),
                    (io[n + 2] = mi(io[n + 2], o)))
                  : ((n = io.length),
                    io.push(r, i, o),
                    e.addListener
                      ? e.addListener(oo)
                      : e.addEventListener('change', oo)),
                (io[n + 3] = e.matches)),
            (jn = 0)
        return io
      }),
      (t.clearMatchMedia = function (t) {
        t || (io.length = 0), (t = io.indexOf(t)) >= 0 && io.splice(t, 4)
      }),
      t
    )
  })()
  ;(Oo.version = '3.6.0'),
    (Oo.saveStyles = function (t) {
      return t
        ? Mn(t).forEach(function (t) {
            if (t && t.style) {
              var e = uo.indexOf(t)
              e >= 0 && uo.splice(e, 4),
                uo.push(t, t.style.cssText, xn.core.getCache(t), jn)
            }
          })
        : uo
    }),
    (Oo.revert = function (t, e) {
      return co(!t, e)
    }),
    (Oo.create = function (t, e) {
      return new Oo(t, e)
    }),
    (Oo.refresh = function (t) {
      return t ? eo() : po(!0)
    }),
    (Oo.update = go),
    (Oo.maxScroll = function (t, e) {
      return li(t, e ? Di : Wi)
    }),
    (Oo.getScrollFunc = function (t, e) {
      return ai(Mn(t)[0], e ? Di : Wi)
    }),
    (Oo.getById = function (t) {
      return Ji[t]
    }),
    (Oo.getAll = function () {
      return $i.slice(0)
    }),
    (Oo.isScrolling = function () {
      return !!ti
    }),
    (Oo.addEventListener = function (t, e) {
      var r = ro[t] || (ro[t] = [])
      ~r.indexOf(e) || r.push(e)
    }),
    (Oo.removeEventListener = function (t, e) {
      var r = ro[t],
        n = r && r.indexOf(e)
      n >= 0 && r.splice(n, 1)
    }),
    (Oo.batch = function (t, e) {
      var r,
        n = [],
        i = {},
        o = e.interval || 0.016,
        s = e.batchMax || 1e9,
        a = function (t, e) {
          var r = [],
            n = [],
            i = xn
              .delayedCall(o, function () {
                e(r, n), (r = []), (n = [])
              })
              .pause()
          return function (t) {
            r.length || i.restart(!0),
              r.push(t.trigger),
              n.push(t),
              s <= r.length && i.progress(1)
          }
        }
      for (r in e)
        i[r] =
          'on' === r.substr(0, 2) && pi(e[r]) && 'onRefreshInit' !== r
            ? a(0, e[r])
            : e[r]
      return (
        pi(s) &&
          ((s = s()),
          Yi(Oo, 'refresh', function () {
            return (s = e.batchMax())
          })),
        Mn(t).forEach(function (t) {
          var e = {}
          for (r in i) e[r] = i[r]
          ;(e.trigger = t), n.push(Oo.create(e))
        }),
        n
      )
    }),
    (Oo.sort = function (t) {
      return $i.sort(
        t ||
          function (t, e) {
            return (
              -1e6 * (t.vars.refreshPriority || 0) +
              t.start -
              (e.start + -1e6 * (e.vars.refreshPriority || 0))
            )
          }
      )
    }),
    ii() && xn.registerPlugin(Oo)
  var Eo = (function () {
    function t(e) {
      vn(this, t),
        (this.speed = 0.035),
        (this.elmt = e),
        (this.leRaf = null),
        (this.isWheeling = null),
        (this.deltaY = 0),
        (this.scrollTop = 0),
        (this.current = 0)
    }
    return (
      bn(t, [
        {
          key: 'init',
          value: function () {
            var t = this
            ;(this.height =
              document.querySelector(this.elmt).clientHeight -
              window.innerHeight),
              (this.deplacement = _n.quickSetter(this.elmt, 'y', 'px')),
              (this.addTicker = function () {
                t.playTicker()
              }),
              _n.ticker.add(this.addTicker)
          },
        },
        {
          key: 'wheel',
          value: function () {
            var t = this
            window.addEventListener(
              'wheel',
              (this.ref = function (e) {
                ;(t.deltaY = e.deltaY),
                  window.clearTimeout(t.isWheeling),
                  (t.isWheeling = setTimeout(function (e) {
                    t.deltaY = 0
                  }, 66))
              })
            )
          },
        },
        {
          key: 'resize',
          value: function () {
            this.height =
              document.querySelector(this.elmt).clientHeight -
              window.innerHeight
          },
        },
        {
          key: 'playTicker',
          value: function () {
            var t = 1 - Math.pow(1 - this.speed, _n.ticker.deltaRatio())
            this.scrollTop + this.deltaY > this.height
              ? (this.scrollTop = this.height)
              : this.scrollTop + this.deltaY < 0
              ? (this.scrollTop = 0)
              : 0 !== this.deltaY && (this.scrollTop += this.deltaY),
              (this.current += (-this.scrollTop - this.current) * t),
              this.deplacement(this.current),
              Oo.update()
          },
        },
        {
          key: 'unwheel',
          value: function () {
            window.removeEventListener('wheel', this.ref)
          },
        },
      ]),
      t
    )
  })()
  function qo() {
    return !(!('ontouchstart' in window) && !navigator.msMaxTouchPoints)
  }
  var Co,
    Lo =
      ((function (t) {
        function e(t) {
          return (e =
            'function' == typeof Symbol && 'symbol' == typeof Symbol.iterator
              ? function (t) {
                  return typeof t
                }
              : function (t) {
                  return t &&
                    'function' == typeof Symbol &&
                    t.constructor === Symbol &&
                    t !== Symbol.prototype
                    ? 'symbol'
                    : typeof t
                })(t)
        }
        function r() {
          var t =
            0 < arguments.length && void 0 !== arguments[0] ? arguments[0] : {}
          for (var e in ((this.hooks = {}),
          (this.options = {}),
          this.defaultOptions))
            this.options[e] = e in t ? t[e] : this.defaultOptions[e]
          this.options.autoload && this.load()
        }
        ;(r.prototype.defaultOptions = {
          selector: 'video[data-yt2html5]',
          attribute: 'data-yt2html5',
          formats: '*',
          autoload: !0,
          withAudio: !1,
        }),
          (r.prototype.globalHooks = {}),
          (r.prototype.getHooks = function (t, e) {
            var r = []
            if (t in this.globalHooks) {
              var n = this.globalHooks[t]
              ;(n = (n = n.filter(function (t) {
                return t.name === e
              })).sort(function (t, e) {
                return t.priority - e.priority
              })),
                (r = r.concat(n))
            }
            if (t in this.hooks) {
              var i = this.hooks[t]
              ;(i = (i = i.filter(function (t) {
                return t.name === e
              })).sort(function (t, e) {
                return t.priority - e.priority
              })),
                (r = r.concat(i))
            }
            return r
          }),
          (r.prototype.addHook = function (t, e) {
            t in this.globalHooks || (this.globalHooks[t] = []),
              t in this.hooks || (this.hooks[t] = []),
              'global' in e && e.global
                ? this.globalHooks[t].push(e)
                : this.hooks[t].push(e)
          }),
          (r.prototype.addAction = function (t, e) {
            var r =
                2 < arguments.length && void 0 !== arguments[2]
                  ? arguments[2]
                  : 10,
              n =
                !!(3 < arguments.length && void 0 !== arguments[3]) &&
                arguments[3]
            this.addHook('actions', {
              name: t,
              callback: e,
              priority: r,
              global: n,
            })
          }),
          (r.prototype.doAction = function (t) {
            for (
              var e = this.getHooks('actions', t),
                r = arguments.length,
                n = Array(1 < r ? r - 1 : 0),
                i = 1;
              i < r;
              i++
            )
              n[i - 1] = arguments[i]
            for (var o = 0; o < e.length; o++) {
              var s
              ;(s = e[o]).callback.apply(s, n)
            }
          }),
          (r.prototype.addFilter = function (t, e) {
            var r =
                2 < arguments.length && void 0 !== arguments[2]
                  ? arguments[2]
                  : 10,
              n =
                !!(3 < arguments.length && void 0 !== arguments[3]) &&
                arguments[3]
            this.addHook('filters', {
              name: t,
              callback: e,
              priority: r,
              global: n,
            })
          }),
          (r.prototype.applyFilters = function (t, e) {
            for (
              var r = this.getHooks('filters', t),
                n = arguments.length,
                i = Array(2 < n ? n - 2 : 0),
                o = 2;
              o < n;
              o++
            )
              i[o - 2] = arguments[o]
            for (var s = 0; s < r.length; s++) {
              var a
              e = (a = r[s]).callback.apply(a, [e].concat(i))
            }
            return e
          }),
          (r.prototype.itagMap = {
            18: '360p',
            22: '720p',
            37: '1080p',
            38: '3072p',
            82: '360p3d',
            83: '480p3d',
            84: '720p3d',
            85: '1080p3d',
            133: '240pna',
            134: '360pna',
            135: '480pna',
            136: '720pna',
            137: '1080pna',
            264: '1440pna',
            298: '720p60',
            299: '1080p60na',
            160: '144pna',
            139: '48kbps',
            140: '128kbps',
            141: '256kbps',
          }),
          (r.prototype.urlToId = function (t) {
            var e = t.match(
              /^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|(?:(?:youtube-nocookie\.com\/|youtube\.com\/)(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/)))([a-zA-Z0-9\-_]*)/
            )
            return Array.isArray(e) && e[1] ? e[1] : t
          }),
          (r.prototype.fetch = function (t) {
            return new Promise(function (e, r) {
              var n = new XMLHttpRequest()
              n.open('GET', t, !0),
                (n.onreadystatechange = function () {
                  4 === this.readyState &&
                    (200 <= this.status && 400 > this.status
                      ? e(this.responseText)
                      : r(this))
                }),
                n.send(),
                (n = null)
            })
          }),
          (r.prototype.getAllowedFormats = function () {
            var t = []
            return (
              Array.isArray(this.options.formats)
                ? (t = this.options.formats)
                : this.itagMap[this.options.formats]
                ? (t = [this.options.formats])
                : '*' === this.options.formats &&
                  (t = Object.values(this.itagMap).sort()),
              t
            )
          }),
          (r.prototype.getElements = function (t) {
            var r = null
            return (
              t &&
                (r =
                  NodeList.prototype.isPrototypeOf(t) ||
                  HTMLCollection.prototype.isPrototypeOf(t)
                    ? t
                    : 'object' === e(t) && 'nodeType' in t && t.nodeType
                    ? [t]
                    : document.querySelectorAll(this.options.selector)),
              (r = Array.from(r || '')),
              this.applyFilters('elements', r)
            )
          }),
          (r.prototype.youtubeDataApiEndpoint = function (t) {
            var e = 'https://yt2html5.com/?id='.concat(t)
            return this.applyFilters('api.endpoint', e, t, null)
          }),
          (r.prototype.parseUriString = function (t) {
            return t.split('&').reduce(function (t, e) {
              var r = e.split('=').map(function (t) {
                return decodeURIComponent(t.replace('+', ' '))
              })
              return (t[r[0]] = r[1]), t
            }, {})
          }),
          (r.prototype.canPlayType = function (t) {
            var e = null,
              r =
                (e = /^audio/i.test(t)
                  ? document.createElement('audio')
                  : document.createElement('video')) &&
                'function' == typeof e.canPlayType
                  ? e.canPlayType(t)
                  : 'unknown'
            return r || 'no'
          }),
          (r.prototype.parseYoutubeMeta = function (t) {
            var e = this,
              r = [],
              n = []
            if ('string' == typeof t)
              try {
                t = JSON.parse(t)
              } catch (t) {
                return null
              }
            var i = t.data || {}
            return (
              (i = this.applyFilters('api.response', i, t)).hasOwnProperty(
                'url_encoded_fmt_stream_map'
              ) &&
                (r = r.concat(
                  i.url_encoded_fmt_stream_map.split(',').map(function (t) {
                    return e.parseUriString(t)
                  })
                )),
              i.player_response.streamingData &&
                i.player_response.streamingData.formats &&
                (r = r.concat(i.player_response.streamingData.formats)),
              i.hasOwnProperty('adaptive_fmts') &&
                (r = r.concat(
                  i.adaptive_fmts.split(',').map(function (t) {
                    return e.parseUriString(t)
                  })
                )),
              i.player_response.streamingData &&
                i.player_response.streamingData.adaptiveFormats &&
                (r = r.concat(i.player_response.streamingData.adaptiveFormats)),
              r.forEach(function (t) {
                if (t && 'itag' in t && e.itagMap[t.itag]) {
                  var r = {
                    _raw: t,
                    itag: t.itag,
                    url: null,
                    label: null,
                    type: 'unknown',
                    mime: 'unknown',
                    hasAudio: !1,
                    browserSupport: 'unknown',
                  }
                  if (
                    ('url' in t && t.url && (r.url = t.url),
                    'audioQuality' in t && t.audioQuality && (r.hasAudio = !0),
                    (r.label =
                      'qualityLabel' in t && t.qualityLabel
                        ? t.qualityLabel
                        : e.itagMap[t.itag]),
                    'mimeType' in t)
                  ) {
                    var i = t.mimeType.match(/^(audio|video)(?:\/([^;]+);)?/i)
                    i[1] && (r.type = i[1]),
                      i[2] && (r.mime = i[2]),
                      (r.browserSupport = e.canPlayType(
                        ''.concat(r.type, '/').concat(r.mime)
                      ))
                  }
                  r.url && n.push(r)
                }
              }),
              (n = this.applyFilters('api.results', n, i))
            )
          }),
          (r.prototype.load = function () {
            var t = this,
              e = this.getElements(this.options.selector)
            e &&
              e.length &&
              e.forEach(function (e) {
                t.loadSingle(e)
              })
          }),
          (r.prototype.loadSingle = function (t) {
            var e = this,
              r =
                1 < arguments.length && void 0 !== arguments[1]
                  ? arguments[1]
                  : null,
              n = r || this.options.attribute
            if (t.getAttribute(n)) {
              var i = this.urlToId(t.getAttribute(n)),
                o = this.youtubeDataApiEndpoint(i)
              this.doAction('api.before', t),
                this.fetch(o)
                  .then(function (r) {
                    if (r) {
                      var n = e.parseYoutubeMeta(r)
                      if (n && Array.isArray(n)) {
                        ;(n = n.filter(function (e) {
                          return e.type === t.tagName.toLowerCase()
                        })).sort(function (t, e) {
                          var r = { unknown: -1, no: -1, maybe: 0, probably: 1 }
                          return r[t.browserSupport] + r[e.browserSupport]
                        }),
                          e.options.withAudio &&
                            (n = n.filter(function (t) {
                              return t.hasAudio
                            }))
                        for (
                          var o = e.getAllowedFormats(),
                            s = null,
                            a = null,
                            u = function (t) {
                              var r = o[t],
                                i = n.filter(function (t) {
                                  return e.itagMap[t.itag] === r
                                })
                              if (i && i.length)
                                return (s = i.shift()), (a = r), 'break'
                            },
                            l = 0;
                          l < o.length && 'break' !== u(l);
                          l++
                        );
                        var c = { src: '', type: '' }
                        ;(s = e.applyFilters('video.stream', s, t, a, n)) &&
                          'url' in s &&
                          s.url &&
                          (c.src = s.url),
                          s.type &&
                            'unknown' !== s.type &&
                            s.mime &&
                            'unknown' !== s.mime &&
                            (c.type = ''.concat(s.type, '/').concat(s.mime)),
                          (c.src = e.applyFilters(
                            'video.source',
                            c.src,
                            s,
                            t,
                            a,
                            n
                          )),
                          c.src &&
                          'function' == typeof c.src.toString &&
                          c.src.toString().length
                            ? ((t.src = c.src),
                              c.type && c.type.length && (t.type = c.type))
                            : console.warn(
                                'YouTubeToHtml5 unable to load video for ID: '.concat(
                                  i
                                )
                              )
                      }
                    }
                  })
                  .finally(function (r) {
                    e.doAction('api.after', t, r)
                  })
            }
          }),
          'object' === e(t) && 'object' === e(t.exports) && (t.exports = r)
      })((Co = { exports: {} }), Co.exports),
      Co.exports),
    Mo = (function () {
      function t(e, r) {
        vn(this, t),
          (this.elmt = e),
          (this.cible = r),
          this.leRaf,
          (this.i = 0),
          (this.avg = 0),
          (this.progress = {}),
          (this.progress.vol = 0)
      }
      return (
        bn(t, [
          {
            key: 'load',
            value: function (t, e, r, n) {
              var i = this
              ;(window.AudioContext =
                window.AudioContext || window.webkitAudioContext),
                (this.context = new AudioContext())
              var o = new XMLHttpRequest()
              o.open('GET', this.elmt.getAttribute('src'), !0),
                (o.responseType = 'arraybuffer'),
                (o.onload = function () {
                  i.context.decodeAudioData(o.response, function (o) {
                    ;(i.source = i.context.createBufferSource()),
                      (i.source.buffer = o),
                      (i.source.loop = !0),
                      (i.gainNode = i.context.createGain()),
                      i.source.connect(i.gainNode),
                      (i.analyser = i.context.createAnalyser()),
                      (i.analyser.fftSize = 32),
                      (i.analyser.smoothingTimeConstant = 0.8),
                      i.gainNode.connect(i.analyser),
                      i.analyser.connect(i.context.destination),
                      (i.frequencyDataLen = i.analyser.frequencyBinCount),
                      (i.frequencyData = new Uint8Array(i.frequencyDataLen)),
                      window.nbSonCharges++,
                      i.go(t, e, r, n)
                  })
                }),
                o.send()
            },
          },
          {
            key: 'play',
            value: function (t, e, r, n) {
              window.quuneFois
                ? this.unmute()
                : ((window.quuneFois = !0),
                  window.nbSonCharges++,
                  this.go(t, e, r, n))
            },
          },
          {
            key: 'go',
            value: function (t, e, r, n) {
              5 == window.nbSonCharges &&
                (t.source.start(0),
                e.source.start(0),
                r.source.start(0),
                n.source.start(0),
                document.querySelector('.toSon1').classList.contains('actif')
                  ? (t.playRAF(),
                    e.mute(),
                    r.mute(),
                    n.mute(),
                    _n.to(t.progress, {
                      duration: 0.5,
                      vol: 1,
                      onUpdate: function () {
                        t.gainNode.gain.setValueAtTime(
                          t.progress.vol,
                          t.context.currentTime
                        )
                      },
                    }),
                    e.gainNode.gain.setValueAtTime(0, e.context.currentTime),
                    r.gainNode.gain.setValueAtTime(0, r.context.currentTime),
                    n.gainNode.gain.setValueAtTime(0, n.context.currentTime))
                  : document
                      .querySelector('.toSon2')
                      .classList.contains('actif')
                  ? (t.mute(),
                    e.playRAF(),
                    r.mute(),
                    n.mute(),
                    _n.to(e.progress, {
                      duration: 0.5,
                      vol: 1,
                      onUpdate: function () {
                        e.gainNode.gain.setValueAtTime(
                          e.progress.vol,
                          e.context.currentTime
                        )
                      },
                    }),
                    t.gainNode.gain.setValueAtTime(0, t.context.currentTime),
                    r.gainNode.gain.setValueAtTime(0, r.context.currentTime),
                    n.gainNode.gain.setValueAtTime(0, n.context.currentTime))
                  : document
                      .querySelector('.toSon3')
                      .classList.contains('actif')
                  ? (t.mute(),
                    e.mute(),
                    r.playRAF(),
                    n.mute(),
                    _n.to(r.progress, {
                      duration: 0.5,
                      vol: 1,
                      onUpdate: function () {
                        r.gainNode.gain.setValueAtTime(
                          r.progress.vol,
                          r.context.currentTime
                        )
                      },
                    }),
                    e.gainNode.gain.setValueAtTime(0, e.context.currentTime),
                    t.gainNode.gain.setValueAtTime(0, t.context.currentTime),
                    n.gainNode.gain.setValueAtTime(0, n.context.currentTime))
                  : document
                      .querySelector('.toSon4')
                      .classList.contains('actif') &&
                    (t.mute(),
                    e.mute(),
                    r.mute(),
                    n.playRAF(),
                    _n.to(n.progress, {
                      duration: 0.5,
                      vol: 1,
                      onUpdate: function () {
                        n.gainNode.gain.setValueAtTime(
                          n.progress.vol,
                          n.context.currentTime
                        )
                      },
                    }),
                    e.gainNode.gain.setValueAtTime(0, e.context.currentTime),
                    r.gainNode.gain.setValueAtTime(0, r.context.currentTime),
                    t.gainNode.gain.setValueAtTime(0, t.context.currentTime)))
            },
          },
          {
            key: 'mute',
            value: function () {
              var t = this
              _n.to(this.progress, {
                duration: 0.5,
                vol: 0,
                onUpdate: function () {
                  t.gainNode.gain.setValueAtTime(
                    t.progress.vol,
                    t.context.currentTime
                  )
                },
              }),
                this.killRAF(),
                _n.to(this.cible, {
                  duration: 1,
                  y: '0px',
                  ease: 'power2.inOut',
                })
            },
          },
          {
            key: 'unmute',
            value: function () {
              var t = this
              _n.to(this.progress, {
                duration: 0.5,
                vol: 1,
                onUpdate: function () {
                  t.gainNode.gain.setValueAtTime(
                    t.progress.vol,
                    t.context.currentTime
                  )
                },
              }),
                this.playRAF()
            },
          },
          {
            key: 'playRAF',
            value: function () {
              for (
                this.leRaf = requestAnimationFrame(this.playRAF.bind(this)),
                  this.analyser.getByteFrequencyData(this.frequencyData),
                  this.i = 0;
                this.i < this.frequencyDataLen;
                this.i++
              )
                this.avg += this.frequencyData[this.i]
              ;(this.avg = this.avg / this.frequencyDataLen),
                _n.to(this.cible, {
                  duration: 0.1,
                  y: 0.0016 * -this.avg * window.innerHeight + 'px',
                  ease: 'none',
                })
            },
          },
          {
            key: 'killRAF',
            value: function () {
              cancelAnimationFrame(this.leRaf)
            },
          },
        ]),
        t
      )
    })(),
    Po = new Eo('#garciScroll')
  _n.registerPlugin(Oo)
  var Ro = document.getElementById('garciScroll')
  ;((navigator.userAgent || navigator.vendor || window.opera).indexOf(
    'Instagram'
  ) > -1 ||
    (function () {
      var t = navigator.userAgent || navigator.vendor || window.opera
      return t.indexOf('FBAN') > -1 || t.indexOf('FBAV') > -1
    })()) &&
    (document.getElementById('inApp').style.display = 'block'),
    qo() || Oo.defaults({ scroller: Ro })
  var Do = !1,
    Wo = !1,
    Bo = window.innerHeight
  ;(window.nbSonCharges = 0), (window.quuneFois = !1)
  var Fo = !1
  window.innerHeight > window.innerWidth && (Fo = !0),
    window.addEventListener('mousemove', function (t) {
      t.x, t.y
    }),
    window.addEventListener('resize', function (t) {
      qo()
        ? (window.innerWidth > window.innerHeight &&
            Fo &&
            document.location.reload(),
          window.innerWidth < window.innerHeight && (Fo = !0))
        : ((Bo = window.innerHeight),
          Oo.getAll().forEach(function (t) {
            t.kill()
          }),
          Xo(),
          Po.resize())
    }),
    Oo.addEventListener('refreshInit', function () {}),
    Oo.addEventListener('refresh', function () {})
  var Io,
    zo,
    Ho,
    No = new Mo(
      document.querySelector('.son1'),
      document.querySelector('.toSon1')
    ),
    Yo = new Mo(
      document.querySelector('.son2'),
      document.querySelector('.toSon2')
    ),
    Vo = new Mo(
      document.querySelector('.son3'),
      document.querySelector('.toSon3')
    ),
    Uo = new Mo(
      document.querySelector('.son4'),
      document.querySelector('.toSon4')
    )
  function Xo() {
    Oo.create({
      trigger: '.SIntro',
      start: 'top top',
      end: '+=' + 4.5 * window.innerHeight + 'px',
      pin: !0,
    })
    var t = []
    document.querySelectorAll('.mot1 span').forEach(function (e) {
      return t.push(e)
    }),
      t.reverse()
    var e = []
    document.querySelectorAll('.mot2 span').forEach(function (t) {
      return e.push(t)
    }),
      e.reverse()
    var r = 0
    t.forEach(function (t) {
      _n.to(t, {
        scrollTrigger: {
          trigger: '.innerSIntro',
          start: 'top 0%',
          end: 'top -' + 1.2 * window.innerHeight + '-=' + r,
          scrub: !0,
        },
        y: '-250%',
        ease: 'none',
      }),
        (r += 0.48 * window.innerHeight)
    })
    var n = 0
    e.forEach(function (t) {
      _n.to(t, {
        scrollTrigger: {
          trigger: '.innerSIntro',
          start: 'top 0%',
          end: 'top -' + 1.2 * window.innerHeight + '-=' + n,
          scrub: !0,
        },
        y: '-250%',
        ease: 'none',
      }),
        (n += 0.48 * window.innerHeight)
    }),
      _n.fromTo(
        '.intro',
        { x: 0.375 * window.innerWidth + 'px', y: -0.35 * Bo + 'px' },
        {
          scrollTrigger: {
            id: 'finaltest',
            trigger: '.innerSIntro',
            start: 'top 0%',
            end: '+=' + 4.5 * window.innerHeight + 'px',
            scrub: !0,
            onLeave: function () {
              document.querySelector('.nom').classList.add('visible')
            },
            onEnterBack: function () {
              document.querySelector('.nom').classList.remove('visible')
            },
          },
          scale: 1,
          x: '0px',
          y: '0px',
          ease: 'none',
        }
      ),
      _n.to('.intro img', {
        scrollTrigger: {
          trigger: '.innerSIntro',
          start: 'top 0%',
          end: '+=' + 4.5 * window.innerHeight + 'px',
          scrub: !0,
        },
        scale: 1,
      }),
      Oo.create({
        trigger: '.biographie',
        start: 'top top',
        end: '+=' + 2 * window.innerHeight + 'px',
        pin: !0,
        pinSpacing: !1,
      }),
      _n.to('.biographie div', {
        scrollTrigger: {
          trigger: '.cita-a',
          start: 'top top+=' + 2 * window.innerHeight + 'px',
          end: '+=' + window.innerHeight + 'px',
          scrub: !0,
        },
        opacity: 0,
        ease: 'none',
      }),
      document.querySelectorAll('.cita .content span').forEach(function (t) {
        t.classList.remove('effet1', 'effet2', 'effet3', 'effet4')
      }),
      document.querySelectorAll('.cita .content span').forEach(function (t) {
        t.classList.add('effet' + parseInt(Math.floor(4 * Math.random()) + 1))
      }),
      document.querySelectorAll('.cita').forEach(function (t) {
        _n.to(t.querySelector('.flexbis'), {
          scrollTrigger: {
            trigger: t,
            start: 'top top',
            end: 'bottom bottom',
            scrub: !0,
            pin: t.querySelector('.flex'),
          },
          y: '-5%',
          ease: 'none',
        })
      }),
      document.querySelectorAll('.effet1').forEach(function (t) {
        _n.to(t, {
          scrollTrigger: {
            trigger: t.closest('.cita'),
            start: 'top 0%',
            end: 'top -40%',
            scrub: !0,
          },
          opacity: 0,
          ease: 'none',
        })
      }),
      document.querySelectorAll('.effet2').forEach(function (t) {
        _n.to(t, {
          scrollTrigger: {
            trigger: t.closest('.cita'),
            start: 'top -20%',
            end: 'top -60%',
            scrub: !0,
          },
          opacity: 0,
          ease: 'none',
        })
      }),
      document.querySelectorAll('.effet3').forEach(function (t) {
        _n.to(t, {
          scrollTrigger: {
            trigger: t.closest('.cita'),
            start: 'top -40%',
            end: 'top -80%',
            scrub: !0,
          },
          opacity: 0,
          ease: 'none',
        })
      }),
      document.querySelectorAll('.effet4').forEach(function (t) {
        _n.to(t, {
          scrollTrigger: {
            trigger: t.closest('.cita'),
            start: 'top -60%',
            end: 'top -100%',
            scrub: !0,
          },
          opacity: 0,
          ease: 'none',
        })
      })
    var i = document.querySelector('.SReal').getBoundingClientRect().left + Bo
    _n.to('.SReal', {
      scrollTrigger: {
        trigger: '.innerSlider',
        start: 'top bottom-=' + i,
        end: '+=' + window.innerWidth + 'px',
        scrub: !0,
      },
      x: '100%',
      ease: 'none',
    }),
      _n.to('.mot2', {
        scrollTrigger: {
          trigger: '.innerSlider',
          start: 'top bottom-=' + (i - window.innerWidth),
          end: '+=' + 3 * window.innerWidth + 'px',
          scrub: !0,
        },
        x: '0%',
        ease: 'none',
      }),
      _n.to('.mot1', {
        scrollTrigger: {
          trigger: '.innerSlider',
          start: 'top bottom-=' + (i - window.innerWidth),
          end: '+=' + 3 * window.innerWidth + 'px',
          scrub: !0,
        },
        x: '-10%',
        ease: 'none',
      }),
      _n.fromTo(
        '.SReal2 .mask',
        { x: '-100%' },
        {
          scrollTrigger: {
            trigger: '.innerSlider',
            start: 'top bottom-=' + i,
            end: '+=' + window.innerWidth + 'px',
            scrub: !0,
          },
          x: '0%',
          ease: 'none',
        }
      ),
      document.querySelectorAll('.grosTitre span').forEach(function (t) {
        var e = t.getBoundingClientRect().left - window.innerWidth
        _n.to(t, {
          scrollTrigger: {
            trigger: '.innerSlider',
            start: 'top top-=' + e,
            end: '+=' + 0.6 * window.innerWidth,
            scrub: !0,
          },
          y: 0,
        })
      }),
      _n.to('.innerSlider', {
        scrollTrigger: {
          trigger: '.innerSlider',
          end:
            '+=' +
            (document.querySelector('.innerSlider').offsetWidth -
              window.innerWidth),
          scrub: !0,
          pin: !0,
        },
        x:
          -document.querySelector('.innerSlider').offsetWidth +
          window.innerWidth +
          'px',
        ease: 'none',
      }),
      _n.set('.debut', { opacity: 0 }),
      _n.to('.debut', {
        scrollTrigger: {
          trigger: '.innerCita2',
          start: 'top 50%',
          end: 'top top',
          scrub: !0,
        },
        opacity: 1,
        ease: 'none',
      }),
      _n.to('.debut', {
        scrollTrigger: {
          trigger: '.innerCita2',
          start: 'bottom bottom',
          end: 'bottom 50%',
          scrub: !0,
        },
        opacity: 0,
        ease: 'none',
        immediateRender: !1,
      }),
      Oo.create({
        trigger: '.SConcert',
        start: 'top bottom',
        end: 'bottom 100%',
        pin: '.debut',
      }),
      document.querySelectorAll('.citaD').forEach(function (t) {
        Oo.create({
          trigger: t,
          start: 'top 50%',
          end: 'bottom 50%',
          onEnter: function () {
            if (t.classList.contains('citaDSpe')) {
              var e = t.getAttribute('data-cit')
              document
                .querySelectorAll('.verb > span')
                [e].classList.contains('actif') ||
                (document
                  .querySelectorAll('.verb > span')
                  [e].classList.add('actif'),
                document.querySelectorAll('.verb > span').forEach(function (t) {
                  t != document.querySelectorAll('.verb > span')[e] &&
                    t.classList.remove('actif')
                }),
                _n.killTweensOf('.verb span'),
                _n.to('.verb span span', {
                  opacity: 0,
                  y: '-40%',
                  duration: 0.2,
                  ease: 'power2.in',
                }),
                _n.fromTo(
                  '.verb span.actif span',
                  { y: '40%' },
                  {
                    opacity: 1,
                    duration: 0.2,
                    y: '0%',
                    delay: 0.2,
                    stagger: 0.05,
                    ease: 'power2.out',
                  }
                ))
            }
          },
          onEnterBack: function () {
            var e = t.getAttribute('data-cit')
            document
              .querySelectorAll('.verb > span')
              [e].classList.contains('actif') ||
              (document
                .querySelectorAll('.verb > span')
                [e].classList.add('actif'),
              document.querySelectorAll('.verb > span').forEach(function (t) {
                t != document.querySelectorAll('.verb > span')[e] &&
                  t.classList.remove('actif')
              }),
              _n.killTweensOf('.verb span'),
              _n.to('.verb span span', {
                opacity: 0,
                y: '-40%',
                duration: 0.2,
                ease: 'power2.in',
              }),
              _n.fromTo(
                '.verb span.actif span',
                { y: '40%' },
                {
                  opacity: 1,
                  duration: 0.2,
                  y: '0%',
                  delay: 0.2,
                  stagger: 0.05,
                  ease: 'power2.out',
                }
              ))
          },
        })
      }),
      _n.to('.truth .mot', {
        scrollTrigger: {
          trigger: '.truth .repere',
          start: 'top 50%',
          end: '+=' + 2 * Bo + 'px',
          pin: '.truth',
          scrub: !0,
        },
        scale: 3.4,
        x: '0%',
        ease: 'none',
      }),
      document.querySelectorAll('.truth span').forEach(function (t) {
        _n.to(t, {
          scrollTrigger: {
            trigger: '.SNextPast',
            start: 'top bottom',
            end: 'top top',
            scrub: !0,
          },
          y: Math.floor(100 * Math.random()) + 0 + '%',
          ease: 'none',
        })
      })
    var o =
      window.innerHeight -
      document.querySelector('.innerImg').offsetHeight -
      0.15 * window.innerWidth
    Oo.create({
      trigger: '.liste',
      start: 'top 0%',
      end: 'bottom bottom-=' + o + 'px',
      pin: '.innerImg',
    }),
      _n.to('.innerImg img', {
        scrollTrigger: {
          trigger: '.liste',
          start: 'top 0%',
          end: 'bottom bottom-=' + o + 'px',
          scrub: !0,
        },
        scale: 1,
        ease: 'none',
      }),
      _n.to('#intermission', {
        scrollTrigger: {
          trigger: '.contenu__inner',
          start: 'top top',
          end: '+=' + window.innerWidth + 'px',
          scrub: !0,
        },
        x: '100vw',
        ease: 'none',
      })
    var s = window.innerWidth + Bo
    _n.to('.notes', {
      scrollTrigger: {
        trigger: '.contenu__inner',
        start: 'top bottom',
        end: '+=' + s + 'px',
        scrub: !0,
      },
      scale: 1.2,
      ease: 'none',
    }),
      _n.to('.notes', {
        scrollTrigger: {
          trigger: '.contenu__inner',
          start: 'top top-=' + window.innerWidth + 'px',
          end: '+=' + window.innerWidth + 'px',
          scrub: !0,
        },
        x: '30%',
        ease: 'none',
        immediateRender: !1,
      }),
      _n.to('.continue', {
        scrollTrigger: {
          trigger: '.contenu__inner',
          start: 'top top',
          end: '+=' + window.innerWidth + 'px',
          scrub: !0,
        },
        opacity: 0,
        ease: 'none',
      }),
      _n.to('.inner_inter .phrase, .inner_inter .inter', {
        scrollTrigger: {
          trigger: '.contenu__inner',
          start: 'top top-=' + window.innerWidth + 'px',
          end: '+=' + window.innerWidth + 'px',
          scrub: !0,
        },
        opacity: 0,
        ease: 'none',
      }),
      Oo.create({
        trigger: '.SRecording',
        start: 'top bottom',
        end: '+=' + (Bo + 2 * window.innerWidth) + 'px',
        onEnter: function () {
          _n.killTweensOf('.uneNote'),
            _n.fromTo(
              '.uneNote',
              { y: '-150%', opacity: 0 },
              {
                y: '0%',
                opacity: 1,
                stagger: -0.1,
                duration: 1.1,
                delay: 0.5,
                ease: 'elastic.out',
              }
            )
        },
        onEnterBack: function () {
          _n.killTweensOf('.uneNote'),
            _n.fromTo(
              '.uneNote',
              { y: '-150%', opacity: 0 },
              {
                y: '0%',
                opacity: 1,
                stagger: -0.1,
                duration: 1.2,
                ease: 'elastic.out',
              }
            )
        },
        onLeave: function () {
          1 == window.quuneFois &&
            (No.mute(),
            Yo.mute(),
            Vo.mute(),
            Uo.mute(),
            document.querySelectorAll('.uneNote').forEach(function (t) {
              t.classList.remove('actif')
            }))
        },
        onLeaveBack: function () {
          1 == window.quuneFois &&
            (No.mute(),
            Yo.mute(),
            Vo.mute(),
            Uo.mute(),
            document.querySelectorAll('.uneNote').forEach(function (t) {
              t.classList.remove('actif')
            }))
        },
      }),
      document.querySelectorAll('.SRecording .lArt').forEach(function (t) {
        var e = t.getBoundingClientRect().left + Bo - 0.8 * window.innerWidth
        _n.fromTo(
          t.querySelector('span'),
          { x: '-100%' },
          {
            scrollTrigger: {
              trigger: '.contenu__inner',
              start: 'top bottom-=' + e,
              end: '+=' + t.offsetWidth,
              scrub: !0,
            },
            x: '0%',
            ease: 'none',
          }
        )
      }),
      _n.to('.contenu__inner', {
        scrollTrigger: {
          trigger: '.contenu__inner',
          end:
            '+=' +
            (document.querySelector('.contenu__inner').offsetWidth -
              window.innerWidth),
          scrub: !0,
          pin: !0,
        },
        x:
          -document.querySelector('.contenu__inner').clientWidth +
          window.innerWidth +
          'px',
        ease: 'none',
      }),
      document.querySelectorAll('.lien span').forEach(function (t) {
        _n.to(t, {
          scrollTrigger: {
            trigger: '.STitular',
            start:
              'top top+=' + (window.innerHeight + 2 * window.innerWidth) + 'px',
            end: '+=' + window.innerWidth,
            scrub: !0,
          },
          x: 0,
          ease: 'none',
        })
      }),
      document.querySelector('.social').classList.remove('bl'),
      _n.set('.social', { backgroundColor: '#000' }),
      _n.to('.social', {
        scrollTrigger: {
          trigger: '.STitular',
          start: 'top top+=' + (window.innerHeight + window.innerWidth) + 'px',
          end: '+=' + window.innerWidth,
          scrub: !0,
          onLeave: function () {
            document.querySelector('.social').classList.add('bl')
          },
          onEnterBack: function () {
            document.querySelector('.social').classList.remove('bl')
          },
        },
        x: '100%',
        ease: 'none',
        immediateRender: !1,
      }),
      _n.fromTo(
        '.div1',
        { y: '250%' },
        {
          scrollTrigger: {
            trigger: '.STitular',
            start:
              'top top+=' + (window.innerHeight + window.innerWidth) + 'px',
            end: '+=' + window.innerWidth,
            scrub: !0,
          },
          y: '0%',
          ease: 'none',
        }
      ),
      _n.fromTo(
        '.div2',
        { y: '200%' },
        {
          scrollTrigger: {
            trigger: '.STitular',
            start:
              'top top+=' + (window.innerHeight + window.innerWidth) + 'px',
            end: '+=' + window.innerWidth,
            scrub: !0,
          },
          y: '0%',
          ease: 'none',
        }
      ),
      _n.fromTo(
        '.div3',
        { y: '150%' },
        {
          scrollTrigger: {
            trigger: '.STitular',
            start:
              'top top+=' + (window.innerHeight + window.innerWidth) + 'px',
            end: '+=' + window.innerWidth,
            scrub: !0,
          },
          y: '0%',
          ease: 'none',
        }
      ),
      _n.fromTo(
        '.div4',
        { y: '100%' },
        {
          scrollTrigger: {
            trigger: '.STitular',
            start:
              'top top+=' + (window.innerHeight + window.innerWidth) + 'px',
            end: '+=' + window.innerWidth,
            scrub: !0,
          },
          y: '0%',
          ease: 'none',
        }
      ),
      Oo.create({
        trigger: '.innerFix',
        start: 'top top',
        end: '+=' + 4.5 * Bo + 'px',
        pin: !0,
      }),
      _n.to('.img .flInvert', {
        scrollTrigger: {
          trigger: '.SProfessor',
          start: 'top +=' + 3 * Bo + 'px',
          end: '+=' + 2 * Bo + 'px',
          scrub: !0,
          onEnter: function () {
            _n.killTweensOf(
              '.STitular .texte div, .amazing div, .innerSvg, .innerSvg img'
            ),
              _n.to('.STitular .texte div', {
                y: '-100%',
                duration: 0.8,
                ease: 'power4.in',
              }),
              _n.to('.amazing div, .innerSvg, .innerSvg img', {
                y: '0%',
                duration: 1.5,
                delay: 0.8,
                opacity: 1,
                ease: 'power4.out',
              })
          },
          onLeaveBack: function () {
            _n.killTweensOf(
              '.STitular .texte div, .amazing div, .innerSvg, .innerSvg img'
            ),
              _n.to('.amazing div, .innerSvg', {
                y: '100%',
                duration: 0.8,
                ease: 'power4.in',
              }),
              _n.to('.innerSvg img', {
                y: '-100%',
                opacity: 0,
                duration: 0.8,
                ease: 'power4.in',
              }),
              _n.to('.STitular .texte div', {
                y: '0%',
                duration: 1.5,
                delay: 0.8,
                ease: 'power4.out',
              })
          },
        },
        opacity: 1,
        y: '0%',
        ease: 'none',
      }),
      _n.to('.innerFix .center', {
        scrollTrigger: {
          trigger: '.STitular',
          start: 'top bottom',
          end: '+=' + Bo,
          scrub: !0,
          onToggle: function (t) {
            t.isActive
              ? document.querySelector('.STitular').classList.add('devant')
              : document.querySelector('.STitular').classList.remove('devant')
          },
        },
        opacity: 1,
        ease: 'none',
      }),
      _n.fromTo(
        '.innerInverse',
        { y: -1 * Bo + 'px' },
        {
          scrollTrigger: {
            trigger: '.STitular',
            start: 'top bottom',
            end: '+=' + Bo,
            scrub: !0,
          },
          y: '0px',
          ease: 'none',
        }
      ),
      _n.fromTo(
        '.img1',
        { x: 0, y: 0.4 * Bo + 'px' },
        {
          scrollTrigger: {
            trigger: '.STitular',
            start: 'top top',
            end: '+=' + Bo,
            scrub: !0,
          },
          x: -0.35 * window.innerWidth + 'px',
          y: '-25%',
        }
      ),
      _n.fromTo(
        '.img1 .transi',
        { scale: 0.5 },
        {
          scrollTrigger: {
            trigger: '.STitular',
            start: 'top top',
            end: '+=' + Bo,
            scrub: !0,
          },
          scale: 0.2,
        }
      ),
      _n.fromTo(
        '.img2',
        { x: 0, y: 0.4 * Bo + 'px' },
        {
          scrollTrigger: {
            trigger: '.STitular',
            start: 'top -40%',
            end: '+=' + Bo,
            scrub: !0,
          },
          x: -0.35 * window.innerWidth + 'px',
          y: '-75%',
        }
      ),
      _n.fromTo(
        '.img2 .transi',
        { scale: 0.5 },
        {
          scrollTrigger: {
            trigger: '.STitular',
            start: 'top -40%',
            end: '+=' + Bo,
            scrub: !0,
          },
          scale: 0.2,
        }
      ),
      _n.fromTo(
        '.img3',
        { x: 0, y: 0.4 * Bo + 'px' },
        {
          scrollTrigger: {
            trigger: '.STitular',
            start: 'top -120%',
            end: '+=' + Bo,
            scrub: !0,
          },
          x: 0.35 * window.innerWidth + 'px',
          y: '-25%',
        }
      ),
      _n.fromTo(
        '.img3 .transi',
        { scale: 0.5 },
        {
          scrollTrigger: {
            trigger: '.STitular',
            start: 'top -120%',
            end: '+=' + Bo,
            scrub: !0,
          },
          scale: 0.2,
        }
      ),
      _n.fromTo(
        '.img4',
        { x: 0, y: 0.4 * Bo + 'px' },
        {
          scrollTrigger: {
            trigger: '.STitular',
            start: 'top -80%',
            end: '+=' + Bo,
            scrub: !0,
          },
          x: 0.35 * window.innerWidth + 'px',
          y: '-75%',
        }
      ),
      _n.fromTo(
        '.img4 .transi',
        { scale: 0.5 },
        {
          scrollTrigger: {
            trigger: '.STitular',
            start: 'top -80%',
            end: '+=' + Bo,
            scrub: !0,
          },
          scale: 0.2,
        }
      ),
      Oo.create({
        trigger: '.SProfessor',
        start: 'top top',
        end: '+=' + 2 * Bo + 'px',
        pin: !0,
        onLeave: function () {
          document.querySelector('.SProfessor h2').classList.add('actif')
        },
        onEnterBack: function () {
          document.querySelector('.SProfessor h2').classList.remove('actif')
        },
      }),
      _n.to('.portrait', {
        scrollTrigger: {
          trigger: '.SProfessor',
          start: 'top bottom',
          end: '+=' + 3 * Bo + 'px',
          scrub: !0,
        },
        scale: 0.265,
        ease: 'none',
      }),
      _n.to('.portrait img', {
        scrollTrigger: {
          trigger: '.SProfessor',
          start: 'top bottom',
          end: '+=' + 3 * Bo + 'px',
          scrub: !0,
        },
        scale: 1,
        ease: 'none',
      }),
      _n.to('.animO', {
        scrollTrigger: {
          trigger: '.SProfessor',
          start: 'top bottom',
          end: '+=' + 3 * Bo + 'px',
          scrub: !0,
        },
        y: '0%',
        ease: 'none',
      }),
      document.querySelectorAll('.SStudent .lArt').forEach(function (t) {
        var e = t.getBoundingClientRect().left + Bo - 0.8 * window.innerWidth
        _n.fromTo(
          t.querySelector('span'),
          { x: '-100%' },
          {
            scrollTrigger: {
              trigger: '.scrollHori2',
              start: 'top bottom-=' + e,
              end: '+=' + t.offsetWidth,
              scrub: !0,
            },
            x: '0%',
            ease: 'none',
          }
        )
      }),
      _n.to('.scrollHori2', {
        scrollTrigger: {
          trigger: '.scrollHori2',
          end:
            '+=' +
            (document.querySelector('.scrollHori2').offsetWidth -
              window.innerWidth),
          pin: !0,
          scrub: !0,
        },
        x:
          -document.querySelector('.scrollHori2').clientWidth +
          window.innerWidth +
          'px',
        ease: 'none',
      })
    var a =
      Bo +
      document.querySelector('.alumnis .listeNom').offsetWidth +
      3 * window.innerWidth
    _n.fromTo(
      '.fullImg img',
      { x: '-95%' },
      {
        x: '95%',
        scale: 1.05,
        ease: 'none',
        scrollTrigger: {
          trigger: '.SAuthor',
          scrub: !0,
          start: 'top top+=' + a + 'px',
          end: '+=' + 2 * window.innerWidth,
        },
      }
    ),
      _n.to('.titreEleves', {
        scrollTrigger: {
          trigger: '.SAuthor',
          start: 'top top+=' + (Bo + 2 * window.innerWidth),
          end: '+=' + 2 * window.innerWidth,
          scrub: !0,
        },
        x: 2 * window.innerWidth + 'px',
        ease: 'none',
      }),
      _n.to('.grosTitre2Fin', {
        scrollTrigger: {
          trigger: '.SAuthor',
          start: 'top top+=' + (Bo + 3 * window.innerWidth),
          end: '+=' + 3 * window.innerWidth,
          scrub: !0,
        },
        x: '-20%',
        ease: 'none',
      }),
      _n.to('.rond', {
        scrollTrigger: {
          trigger: '.SAuthor',
          start: 'top top+=' + (Bo + window.innerWidth),
          end: '+=' + window.innerWidth,
          scrub: !0,
          onUpdate: function (t) {
            0 != _n.getProperty('.rond', 'scale') &&
              _n.set('.rond img', {
                scale: 1 / _n.getProperty('.rond', 'scale'),
              })
          },
        },
        x: window.innerWidth + 'px',
        scale: 1,
        ease: 'none',
      }),
      _n.to('.innerRond', {
        scrollTrigger: {
          trigger: '.SAuthor',
          start: 'top top+=' + Bo + 'px',
          end: '+=' + Bo + 'px',
          scrub: !0,
          onEnter: function () {
            document.querySelector('.rond').classList.add('visible')
          },
          onLeaveBack: function () {
            document.querySelector('.rond').classList.remove('visible')
          },
        },
        y: Bo + 'px',
        opacity: 0,
        ease: 'none',
      }),
      _n.to('.rond img', {
        scrollTrigger: {
          trigger: '.SAuthor',
          start: 'top top+=' + (Bo + window.innerWidth),
          end: '+=' + (window.innerWidth + Bo) + 'px',
          scrub: !0,
        },
        rotation: -120,
        ease: 'none',
      })
    var u = window.innerWidth
    Oo.create({
      trigger: '.apparition',
      start: 'top top',
      end: '+=' + u + 'px',
      pin: !0,
    }),
      _n.fromTo(
        '.grosTitre22',
        { y: -1 * Bo + 'px' },
        {
          scrollTrigger: {
            trigger: '.haut',
            start: 'top bottom',
            end: 'top top',
            scrub: !0,
          },
          y: '0px',
          ease: 'none',
        }
      ),
      _n.to('.SAuthor .gauche, .SAuthor .droite', {
        scrollTrigger: {
          trigger: '.SAuthor',
          start: 'top 100%',
          end: '+=' + (window.innerWidth + Bo) + 'px',
          scrub: !0,
        },
        x: '0%',
        ease: 'none',
      }),
      _n.to('.comp', {
        scrollTrigger: {
          trigger: '.haut',
          start: 'top top+=' + window.innerWidth,
          end: '+=' + 2 * window.innerWidth + 'px',
          scrub: !0,
        },
        y: '0%',
        ease: 'none',
      }),
      _n.set('.zoom', { opacity: 1 }),
      _n.to('.innerImgZ img', {
        scrollTrigger: {
          trigger: '.innerImgZ',
          start: 'top 100%',
          end: 'top bottom',
          endTrigger: '.finAuthor',
          scrub: !0,
          onUpdate: function (t) {
            t.progress > 0.5 &&
              _n.set('.zoom', {
                opacity: _n.utils.mapRange(0.5, 1, 1, 0.15, t.progress),
                blur: _n.utils.mapRange(0.5, 1, 0, 20, t.progress),
              })
          },
        },
        scale: 0.9,
        ease: 'none',
      }),
      Oo.create({
        trigger: '.people',
        start: 'top top',
        end: '+=' + window.innerWidth + 'px',
        onUpdate: function (t) {
          _n.set('.zoom', {
            opacity: _n.utils.mapRange(0, 1, 0.15, 0, t.progress),
          })
        },
      })
    var l =
      document.querySelector('.SContact').offsetHeight +
      document.querySelector('.finAuthor').offsetHeight
    Oo.create({
      trigger: '.innerImgZ',
      start: 'top top',
      end: '+=' + l + Bo + 'px',
      pin: !0,
      pinSpacing: !1,
    }),
      qo() ||
        (document.querySelectorAll('.people span').forEach(function (t) {
          var e = t.getBoundingClientRect().left - 1.25 * window.innerWidth
          _n.to(t, {
            scrollTrigger: {
              trigger: '.people',
              start: 'top top-=' + e,
              end: '+=' + window.innerWidth,
              scrub: !0,
            },
            y: '0%',
            scale: 1,
            ease: 'elastic.inOut',
          })
        }),
        _n.to('.people', {
          scrollTrigger: {
            trigger: '.people',
            end:
              '+=' +
              (document.querySelector('.people').offsetWidth -
                window.innerWidth),
            scrub: !0,
            pin: !0,
          },
          x:
            -document.querySelector('.people').offsetWidth +
            window.innerWidth +
            'px',
          ease: 'none',
        }))
  }
  function jo(t, e, r) {
    if (!Do) {
      ;(document.getElementById('volet').style.display = 'block'), (Do = !0)
      var n = 0
      '.SDeb' == t && document.querySelector('.nom').classList.remove('visible')
      var i = document.querySelector(t).getBoundingClientRect().top > 0 ? 1 : -1
      '.SProfessor' == t && 1 == i && (e = 2 * Bo + 1),
        !0 !== r
          ? _n.fromTo(
              '#volet',
              { y: 100 * i + '%' },
              {
                y: 100 * -i + '%',
                duration: 2,
                ease: 'power4.inOut',
                onComplete: function () {
                  ;(Do = !1),
                    (document.getElementById('volet').style.display = 'none')
                },
              }
            )
          : ((n = 300),
            _n.to('#volet', {
              y: 100 * -i + '%',
              duration: 1.6,
              ease: 'power4.inOut',
              delay: 1,
              onComplete: function () {
                ;(Do = !1),
                  (document.querySelector('.notesB').style.display = 'none'),
                  (document.getElementById('volet').style.display = 'none')
              },
            })),
        _n.delayedCall(1, function () {
          ;(document.getElementById('menu').style.display = 'none'),
            '.SDeb' != t &&
              document.querySelector('.nom').classList.add('visible')
          var r = 0
          r = qo() ? -window.scrollY : Po.current
          var o = document.querySelector(t).getBoundingClientRect().top - r + e
          qo()
            ? window.scrollTo(0, o)
            : ((Po.scrollTop = o), (Po.current = i * (200 + n) - o))
        })
    }
  }
  window.addEventListener('DOMContentLoaded', function () {
    if (
      (No.load(No, Yo, Vo, Uo),
      Yo.load(No, Yo, Vo, Uo),
      Vo.load(No, Yo, Vo, Uo),
      Uo.load(No, Yo, Vo, Uo),
      qo() && window.scrollTo(0, 0),
      document.getElementById('annee').addEventListener('change', function (t) {
        ;(document.querySelector('.toAnnee').innerHTML = t.target.value),
          _n.to('.pastE.affiche', {
            opacity: 0,
            duration: 0.2,
            stagger: 0.06,
            onComplete: function () {
              document.querySelectorAll('.pastE').forEach(function (t) {
                t.style.display = 'none'
              }),
                document
                  .querySelectorAll(
                    '.pastE[data-annee="' +
                      document.getElementById('annee').value +
                      '"]'
                  )
                  .forEach(function (t) {
                    ;(t.style.display = 'block'), t.classList.add('affiche')
                  }),
                Oo.getAll().forEach(function (t) {
                  t.kill()
                }),
                Xo(),
                alert('cas: annee'),
                Po.resize(),
                _n.to(
                  '.pastE[data-annee="' +
                    document.getElementById('annee').value +
                    '"]',
                  { opacity: 1, duration: 0.2, stagger: 0.1 }
                )
            },
          })
      }),
      document.addEventListener('click', function (t) {
        if (
          (Wo || ((Wo = !0), new Lo({ withAudio: !0 })),
          t.target.closest('.play1'))
        )
          _n.to('.innerVideo .inner', {
            duration: 0.5,
            ease: 'power2.inOut',
            x: '-100%',
          }),
            _n.to('.innerVideo .innerBtn, .innerVideo .inner img', {
              duration: 0.5,
              ease: 'power2.inOut',
              x: '100%',
            }),
            document.getElementById('vid1').play()
        else if (t.target.closest('.play2'))
          _n.to('.innerPlayer .inner', {
            duration: 0.5,
            ease: 'power2.inOut',
            x: '-100%',
          }),
            _n.to('.innerPlayer .innerBtn, .innerPlayer .inner img', {
              duration: 0.5,
              ease: 'power2.inOut',
              x: '100%',
            }),
            document.getElementById('vid2').play()
        else if (t.target.closest('.img') && 0 == Do) {
          ;(Do = !0), Po.unwheel()
          var e = t.target.closest('.img'),
            r = e.getAttribute('data-img') - 1
          document
            .getElementById('carousel')
            .querySelectorAll('.bigPic > div')
            [r].classList.add('current'),
            _n.set('.bigPic .current', { x: '0%' }),
            _n.set('.bigPic .current img', { x: '0%' }),
            document
              .getElementById('carousel')
              .querySelectorAll('.titresC > h3')
              [r].classList.add('current'),
            document
              .getElementById('carousel')
              .querySelectorAll('.artist > div')
              [r].classList.add('current'),
            document
              .querySelectorAll('.img, .amazing, .innerFl')
              .forEach(function (t) {
                t != e && _n.to(t, { opacity: 0, duration: 0.2 })
              }),
            _n.to('.innerInverse', { opacity: 0, duration: 0.2 }),
            e.classList.add('devant'),
            document.querySelector('.nom').classList.remove('visible'),
            _n.killTweensOf(e),
            _n.killTweensOf(e.querySelector('.transi')),
            _n.to(e, {
              y: '-50%',
              x: 0.25 * window.innerWidth + 'px',
              duration: 1,
              ease: 'power4.inOut',
              onComplete: function () {
                ;(document.getElementById('carousel').style.display = 'flex'),
                  _n.to('#carousel', {
                    opacity: 1,
                    duration: 0.2,
                    onComplete: function () {
                      _n.set(e, { opacity: 0 })
                    },
                  }),
                  (Do = !1)
              },
            }),
            _n.to(e.querySelector('.transi'), {
              scale: 1,
              duration: 1,
              ease: 'power4.inOut',
              onComplete: function () {
                _n.killTweensOf(e)
              },
            })
        } else if (t.target.closest('.close')) {
          var n = window.scrollY
          Oo.getAll().forEach(function (t) {
            t.kill()
          }),
            Xo(),
            qo() ? window.scrollTo(0, n) : Po.wheel(),
            _n.to('#carousel', {
              opacity: 0,
              duration: 0.4,
              onComplete: function () {
                ;(document.getElementById('carousel').style.display = 'none'),
                  document
                    .getElementById('carousel')
                    .querySelectorAll('*')
                    .forEach(function (t) {
                      t.removeAttribute('style', '')
                    }),
                  document
                    .querySelector('.bigPic .current')
                    .classList.remove('current'),
                  document
                    .querySelector('.titresC .current')
                    .classList.remove('current'),
                  document
                    .querySelector('.artist .current')
                    .classList.remove('current'),
                  document
                    .querySelectorAll('.img, .amazing, .innerFl')
                    .forEach(function (t) {
                      _n.to(t, { opacity: 1, duration: 0.2 })
                    }),
                  _n.to('.innerInverse', { opacity: 1, duration: 0.2 })
              },
            }),
            document.querySelector('.img.devant').classList.remove('devant'),
            document.querySelector('.nom').classList.add('visible')
        } else if (t.target.closest('.toNext'))
          Do ||
            ((Do = !0),
            (u = document.querySelector('.carouI.current')),
            (l = document.querySelector('.nomOrgue.current')),
            (c =
              null !==
              document.querySelector('.carouI.current').nextElementSibling
                ? document.querySelector('.carouI.current').nextElementSibling
                : document.querySelectorAll('.carouI')[0]),
            (d =
              null !==
              document.querySelector('.nomOrgue.current').nextElementSibling
                ? document.querySelector('.nomOrgue.current').nextElementSibling
                : document.querySelectorAll('.nomOrgue')[0]),
            (p = document.querySelector('.artist .current')),
            (f =
              null !==
              document.querySelector('.artist .current').nextElementSibling
                ? document.querySelector('.artist .current').nextElementSibling
                : document.querySelectorAll('.artist div')[0]),
            _n.to(u, { duration: 0.5, x: '-100%', ease: 'power2.inOut' }),
            _n.to(u.querySelector('img'), {
              duration: 0.5,
              x: '98%',
              ease: 'power2.inOut',
            }),
            _n.fromTo(
              c,
              { x: '100%' },
              { duration: 0.5, x: '0%', ease: 'power2.inOut' }
            ),
            _n.fromTo(
              c.querySelector('img'),
              { x: '-98%' },
              {
                duration: 0.5,
                x: '0%',
                ease: 'power2.inOut',
                onComplete: function () {
                  u.classList.remove('current'), c.classList.add('current')
                },
              }
            ),
            _n.to(l.querySelectorAll('span'), {
              stagger: 0.05,
              x: -0.03 * window.innerWidth + 'px',
              opacity: 0,
              duration: 0.3,
              ease: 'power2.in',
            }),
            _n.fromTo(
              d.querySelectorAll('span'),
              { x: 0.03 * window.innerWidth + 'px' },
              {
                stagger: -0.05,
                x: 0,
                opacity: 1,
                delay: 0.5,
                duration: 0.3,
                ease: 'power2.out',
                onComplete: function () {
                  l.classList.remove('current'),
                    d.classList.add('current'),
                    (Do = !1)
                },
              }
            ),
            _n.to(p, {
              opacity: 0,
              duration: 0.3,
              onComplete: function () {
                p.classList.remove('current'), f.classList.add('current')
              },
            }),
            _n.fromTo(
              f,
              { opacity: 0 },
              { opacity: 1, duration: 0.3, delay: 0.7 }
            ))
        else if (t.target.closest('.toPrev'))
          Do ||
            ((Do = !0),
            (function () {
              var t = document.querySelector('.carouI.current'),
                e = document.querySelector('.nomOrgue.current'),
                r =
                  null !==
                  document.querySelector('.carouI.current')
                    .previousElementSibling
                    ? document.querySelector('.carouI.current')
                        .previousElementSibling
                    : document.querySelectorAll('.carouI')[
                        document.querySelectorAll('.carouI').length - 1
                      ],
                n =
                  null !==
                  document.querySelector('.nomOrgue.current')
                    .previousElementSibling
                    ? document.querySelector('.nomOrgue.current')
                        .previousElementSibling
                    : document.querySelectorAll('.nomOrgue')[
                        document.querySelectorAll('.nomOrgue').length - 1
                      ],
                i = document.querySelector('.artist .current'),
                o =
                  null !==
                  document.querySelector('.artist .current')
                    .previousElementSibling
                    ? document.querySelector('.artist .current')
                        .previousElementSibling
                    : document.querySelectorAll('.artist div')[
                        document.querySelectorAll('.artist div').length - 1
                      ]
              _n.to(t, { duration: 0.5, x: '100%', ease: 'power2.inOut' }),
                _n.to(t.querySelector('img'), {
                  duration: 0.5,
                  x: '-98%',
                  ease: 'power2.inOut',
                }),
                _n.fromTo(
                  r,
                  { x: '-100%' },
                  { duration: 0.5, x: '0%', ease: 'power2.inOut' }
                ),
                _n.fromTo(
                  r.querySelector('img'),
                  { x: '98%' },
                  {
                    duration: 0.5,
                    x: '0%',
                    ease: 'power2.inOut',
                    onComplete: function () {
                      t.classList.remove('current'), r.classList.add('current')
                    },
                  }
                ),
                _n.to(e.querySelectorAll('span'), {
                  stagger: 0.05,
                  x: 0.03 * window.innerWidth + 'px',
                  opacity: 0,
                  duration: 0.3,
                  ease: 'power2.in',
                }),
                _n.fromTo(
                  n.querySelectorAll('span'),
                  { x: -0.03 * window.innerWidth + 'px' },
                  {
                    stagger: -0.05,
                    x: 0,
                    opacity: 1,
                    delay: 0.5,
                    duration: 0.3,
                    ease: 'power2.out',
                    onComplete: function () {
                      e.classList.remove('current'),
                        n.classList.add('current'),
                        (Do = !1)
                    },
                  }
                ),
                _n.to(i, {
                  opacity: 0,
                  duration: 0.3,
                  onComplete: function () {
                    i.classList.remove('current'), o.classList.add('current')
                  },
                }),
                _n.fromTo(
                  o,
                  { opacity: 0 },
                  { opacity: 1, duration: 0.3, delay: 0.7 }
                )
            })())
        else if (t.target.closest('.toMenu') && !Do) {
          ;(Do = !0),
            (document.getElementById('volet').style.display = 'block'),
            _n.fromTo(
              '#volet',
              { y: '-100%' },
              {
                y: '100%',
                duration: 2,
                ease: 'power4.inOut',
                onComplete: function () {
                  ;(Do = !1),
                    (document.getElementById('volet').style.display = 'none')
                },
              }
            ),
            _n.delayedCall(1, function () {
              ;(document.getElementById('menu').style.display = 'flex'),
                _n.fromTo(
                  'hr',
                  { opacity: 0 },
                  {
                    opacity: 1,
                    ease: 'power2.inOut',
                    stagger: 0.06,
                    duration: 1,
                  }
                )
            }),
            _n.delayedCall(0.7, function () {
              _n.fromTo(
                '.entree .nom',
                { y: '-80%' },
                { y: '0%', ease: 'power4.out', stagger: 0.06, duration: 1.8 }
              ),
                _n.fromTo(
                  '.entree .part',
                  { y: '-100%', x: '-100%', opacity: 0 },
                  {
                    y: '0%',
                    opacity: 1,
                    ease: 'power4.out',
                    stagger: 0.06,
                    duration: 2.5,
                  }
                )
            }),
            document.querySelectorAll('.entree button').forEach(function (t) {
              t.classList.remove('passe')
            })
          var i = 0
          document.querySelectorAll('.passage').forEach(function (t) {
            t.getBoundingClientRect().top < window.innerHeight && i++
          })
          for (var o = 0; o <= i; o++)
            document
              .querySelectorAll('.entree button')
              [o].classList.add('passe')
        } else if (t.target.closest('.toClose') && !Do)
          (Do = !0),
            (document.getElementById('volet').style.display = 'block'),
            _n.fromTo(
              '#volet',
              { y: '100%' },
              {
                y: '-100%',
                duration: 2,
                ease: 'power4.inOut',
                onComplete: function () {
                  ;(Do = !1),
                    (document.getElementById('volet').style.display = 'none')
                },
              }
            ),
            _n.delayedCall(1, function () {
              document.getElementById('menu').style.display = 'none'
            }),
            _n.to('.entree .nom', {
              y: '-80%',
              ease: 'power4.in',
              stagger: -0.06,
              duration: 1,
            })
        else if (t.target.closest('.toSection')) {
          var s = t.target.closest('.toSection').getAttribute('data-ancre'),
            a = 0
          '.SAuthor' == s && (a = window.innerWidth),
            '.STitular' == s &&
              (_n.killTweensOf(
                '.STitular .texte div, .amazing div, .innerSvg, .innerSvg img'
              ),
              _n.set('.amazing div, .innerSvg', { y: '100%' }),
              _n.set('.innerSvg img', { y: '-100%', opacity: 0 }),
              _n.set('.STitular .texte div', { y: '0%' })),
            jo(s, a, !1)
        } else if (t.target.closest('.nomL')) {
          jo('.SDeb', 0, !1),
            document.querySelector('.nom').classList.remove('visible')
        } else
          t.target.closest('.toSon1')
            ? t.target.closest('.toSon1').classList.contains('actif')
              ? (t.target.closest('.toSon1').classList.remove('actif'),
                No.mute())
              : (t.target.closest('.toSon1').classList.add('actif'),
                No.play(No, Yo, Vo, Uo))
            : t.target.closest('.toSon2')
            ? t.target.closest('.toSon2').classList.contains('actif')
              ? (t.target.closest('.toSon2').classList.remove('actif'),
                Yo.mute())
              : (t.target.closest('.toSon2').classList.add('actif'),
                Yo.play(No, Yo, Vo, Uo))
            : t.target.closest('.toSon3')
            ? t.target.closest('.toSon3').classList.contains('actif')
              ? (t.target.closest('.toSon3').classList.remove('actif'),
                Vo.mute())
              : (t.target.closest('.toSon3').classList.add('actif'),
                Vo.play(No, Yo, Vo, Uo))
            : t.target.closest('.toSon4') &&
              (t.target.closest('.toSon4').classList.contains('actif')
                ? (t.target.closest('.toSon4').classList.remove('actif'),
                  Uo.mute())
                : (t.target.closest('.toSon4').classList.add('actif'),
                  Uo.play(No, Yo, Vo, Uo)))
        var u, l, c, d, p, f
      }),
      qo() ||
        (Oo.scrollerProxy(Ro, {
          scrollTop: function (t) {
            return arguments.length && (Po.current = -t), -Po.current
          },
          getBoundingClientRect: function () {
            return {
              top: 0,
              left: 0,
              width: window.innerWidth,
              height: window.innerHeight,
            }
          },
        }),
        Po.init()),
      setTimeout(function () {
        document.body.classList.remove('load'),
          _n.to('.scroll', {
            opacity: 1,
            ease: 'power2.out',
            duration: 1,
            delay: 3,
          }),
          jo('.SDeb', 0, !0),
          qo()
            ? (window.scrollTo(0, 0),
              (function () {
                if (window.innerWidth > window.innerHeight) {
                  Oo.getAll().forEach(function (t) {
                    t.kill()
                  }),
                    Xo()
                }
              })())
            : (_n.fromTo(
                '.nota',
                { y: '-150%', opacity: 0 },
                {
                  y: '0%',
                  opacity: 1,
                  stagger: 0.1,
                  duration: 1.1,
                  delay: 0.5,
                  ease: 'elastic.out',
                }
              ),
              _n.to('.notesB', {
                y: '-30%',
                duration: 1.6,
                delay: 1,
                ease: 'power4.inOut',
              }),
              Xo(),
              Po.wheel(),
              Po.resize())
      }, 1e3),
      qo())
    ) {
      window.addEventListener('scroll', function (t) {
        window.innerHeight > Bo &&
          ((Bo = window.innerHeight),
          Oo.getAll().forEach(function (t) {
            t.kill()
          }),
          Xo())
      }),
        document.addEventListener(
          'touchstart',
          function (r) {
            var n = (function (t) {
              return t.touches || t.originalEvent.touches
            })(r)[0]
            ;(t = n.clientX), (e = n.clientY)
          },
          !1
        ),
        document.addEventListener(
          'touchmove',
          function (r) {
            if (t && e) {
              var n = r.touches[0].clientX,
                i = r.touches[0].clientY,
                o = t - n,
                s = e - i
              Math.abs(o) > Math.abs(s) ||
                (s > 0 &&
                  (document
                    .querySelector('.leftHead')
                    .classList.contains('actif') ||
                    document
                      .querySelector('.leftHead')
                      .classList.add('actif'))),
                (t = null),
                (e = null)
            }
          },
          !1
        )
      var t = null,
        e = null
    }
  }),
    (Io = _n.utils.checkPrefix('filter')),
    (zo = /blur\((.+)?px\)/),
    (Ho = function (t) {
      return (_n.getProperty(t, Io) || '').match(zo) || []
    }),
    _n.registerPlugin({
      name: 'blur',
      get: function (t) {
        return +Ho(t)[1] || 0
      },
      init: function (t, e) {
        var r,
          n = _n.getProperty(t, Io),
          i = 'blur(' + e + 'px)',
          o = Ho(t)[0]
        'none' === n && (n = ''),
          o
            ? ((r = n.indexOf(o)),
              (e = n.substr(0, r) + i + n.substr(r + o.length)))
            : ((e = n + i), (n += n ? ' blur(0px)' : 'blur(0px)')),
          (this.target = t),
          (this.interp = _n.utils.interpolate(n, e))
      },
      render: function (t, e) {
        e.target.style[Io] = e.interp(t)
      },
    })
})()
