  int main() {
    int T, * k, n, N, * m, i, l;
    k = new int[0];
    m = new int[0];

    scanf("%d", & T);
    for (i = 0; i < T; i++) scanf("%d%d", & k[i], & m[i]);

    for (i = 0; i < T; i++) {
      l = k[i] - 1;
      if (l >= 2) n = (ceil(l / 3 + 1) * 3) + l % 3;
      else n = k[i];
      N = n * n - 1;
      printf("%d\n", N % m[i]);
    }

    return 0;
  }
